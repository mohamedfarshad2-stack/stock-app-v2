<?php

namespace App\Filament\Pages;

use App\Exports\HELOSFinanceCategoryTemplateExport;
use App\Exports\HELOSMoneyRecordTemplateExport;
use App\Models\BusinessUnit;
use App\Models\FinanceCategory;
use App\Models\MoneyRecord;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class HELOSImportCenter extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-upload';
    protected static ?string $navigationGroup = 'HELOS';
    protected static ?string $navigationLabel = 'Import Center';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?int $navigationSort = 50;
    protected static ?string $slug = 'helos-import-center';
    protected static string $view = 'filament.pages.helos-import-center';

    public $file;
    public $category_file;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\FileUpload::make('file')
                ->label('Upload Money Records Excel File')
                ->disk('public')
                ->directory('helos-imports')
                ->acceptedFileTypes([
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-excel',
                ]),

            Forms\Components\FileUpload::make('category_file')
                ->label('Upload Finance Categories Excel File')
                ->disk('public')
                ->directory('helos-imports')
                ->acceptedFileTypes([
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-excel',
                ]),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadMoneyTemplate')
                ->label('Download Money Records Sample')
                ->icon('heroicon-o-download')
                ->action(fn () => Excel::download(new HELOSMoneyRecordTemplateExport(), 'helos-money-record-template.xlsx')),
            Action::make('downloadCategoryTemplate')
                ->label('Download Cost Category Sample')
                ->icon('heroicon-o-download')
                ->action(fn () => Excel::download(new HELOSFinanceCategoryTemplateExport(), 'helos-cost-category-template.xlsx')),
        ];
    }

    public function import(): void
    {
        $rows = $this->getRowsFromUpload('file', 'Please upload a money records file first.');

        if ($rows === null) {
            return;
        }

        if (count($rows) <= 1) {
            Notification::make()->title('Excel file has no data rows.')->danger()->send();
            return;
        }

        $businessUnitMap = BusinessUnit::query()->pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [mb_strtolower(trim((string) $name)) => $id]);

        $categoryMap = FinanceCategory::query()->pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [mb_strtolower(trim((string) $name)) => $id]);

        $imported = 0;
        $errors = [];

        $headerMap = $this->buildHeaderMap($rows[0] ?? []);

        foreach (array_slice($rows, 1) as $index => $row) {
            $line = $index + 2;

            if ($this->isRowEmpty($row)) {
                continue;
            }

            $businessUnitId = $businessUnitMap[mb_strtolower(trim((string) $this->getMappedValue($row, $headerMap, ['business unit', 'business_unit'], 1)))] ?? null;
            $categoryId = $categoryMap[mb_strtolower(trim((string) $this->getMappedValue($row, $headerMap, ['category', 'finance category', 'finance_category'], 2)))] ?? null;

            if (! $businessUnitId) {
                $errors[] = "Row {$line}: Business Unit not found.";
                continue;
            }

            if (! $categoryId) {
                $errors[] = "Row {$line}: Category not found.";
                continue;
            }

            $type = strtolower(trim((string) $this->getMappedValue($row, $headerMap, ['type', 'transaction type'], 3)));
            if (! in_array($type, ['income', 'expense', 'transfer', 'receivable', 'payable'], true)) {
                $errors[] = "Row {$line}: Type must be income/expense/transfer/receivable/payable.";
                continue;
            }

            $amount = (float) $this->getMappedValue($row, $headerMap, ['amount', 'value'], 7);
            if ($amount <= 0) {
                $errors[] = "Row {$line}: Amount must be greater than 0.";
                continue;
            }

            $recordDate = ! empty($this->getMappedValue($row, $headerMap, ['record date', 'date', 'record_date'], 0))
                ? (string) $this->getMappedValue($row, $headerMap, ['record date', 'date', 'record_date'], 0)
                : now()->toDateString();

            $referenceNo = $this->getMappedValue($row, $headerMap, ['reference no', 'reference', 'reference_no'], 10);

            $isDuplicate = MoneyRecord::query()
                ->whereDate('record_date', $recordDate)
                ->where('business_unit_id', $businessUnitId)
                ->where('finance_category_id', $categoryId)
                ->where('type', $type)
                ->where('amount', $amount)
                ->when($referenceNo, fn ($q) => $q->where('reference_no', $referenceNo))
                ->exists();

            if ($isDuplicate) {
                $errors[] = "Row {$line}: Duplicate transaction detected.";
                continue;
            }

            MoneyRecord::create([
                'record_date' => $recordDate,
                'business_unit_id' => $businessUnitId,
                'finance_category_id' => $categoryId,
                'user_id' => Auth::id(),
                'type' => $type,
                'amount' => $amount,
                'payment_method' => $this->getMappedValue($row, $headerMap, ['payment method', 'payment_method'], 5),
                'reference_no' => $referenceNo,
                'description' => $this->getMappedValue($row, $headerMap, ['description', 'notes'], 11),
                'status' => 'approved',
            ]);

            $imported++;
        }

        $this->logImportAudit('money_records', count($rows) - 1, $imported, $errors);
        $this->notifyImportResult("Imported {$imported} rows.", $errors);
        $this->form->fill(['file' => null]);
    }

    public function importCategories(): void
    {
        $rows = $this->getRowsFromUpload('category_file', 'Please upload a category file first.');

        if ($rows === null) {
            return;
        }

        if (count($rows) <= 1) {
            Notification::make()->title('Category Excel file has no data rows.')->danger()->send();
            return;
        }

        $categoryIdMap = FinanceCategory::query()->pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [mb_strtolower(trim((string) $name)) => $id]);

        $imported = 0;
        $errors = [];

        $headerMap = $this->buildHeaderMap($rows[0] ?? []);

        foreach (array_slice($rows, 1) as $index => $row) {
            $line = $index + 2;

            if ($this->isRowEmpty($row)) {
                continue;
            }

            $name = trim((string) $this->getMappedValue($row, $headerMap, ['name', 'category'], 0));
            $type = strtolower(trim((string) $this->getMappedValue($row, $headerMap, ['type'], 2)));
            $parentName = trim((string) $this->getMappedValue($row, $headerMap, ['parent', 'parent category', 'parent_id'], 3));
            $isActive = in_array(strtolower((string) $this->getMappedValue($row, $headerMap, ['active', 'is_active'], 4)), ['1', 'true', 'yes'], true);

            if ($name === '') {
                $errors[] = "Row {$line}: Name is required.";
                continue;
            }

            if (! in_array($type, ['income', 'expense', 'transfer', 'receivable', 'payable'], true)) {
                $errors[] = "Row {$line}: Type must be income/expense/transfer/receivable/payable.";
                continue;
            }

            $parentId = null;
            if ($parentName !== '') {
                $parentId = $categoryIdMap[mb_strtolower($parentName)] ?? null;
                if (! $parentId) {
                    $errors[] = "Row {$line}: Parent Category not found.";
                    continue;
                }
            }

            FinanceCategory::updateOrCreate(
                ['name' => $name, 'type' => $type],
                [
                    'code' => trim((string) $this->getMappedValue($row, $headerMap, ['code'], 1)) ?: null,
                    'parent_id' => $parentId,
                    'is_active' => $isActive,
                ]
            );

            $imported++;
        }

        $this->logImportAudit('finance_categories', count($rows) - 1, $imported, $errors);
        $this->notifyImportResult("Imported {$imported} categories.", $errors);
        $this->form->fill(['category_file' => null]);
    }

    private function buildHeaderMap(array $headerRow): array
    {
        $map = [];
        foreach ($headerRow as $i => $header) {
            $normalized = mb_strtolower(trim((string) $header));
            if ($normalized !== '') {
                $map[$normalized] = $i;
            }
        }

        return $map;
    }

    private function getMappedValue(array $row, array $headerMap, array $aliases, int $fallbackIndex): mixed
    {
        foreach ($aliases as $alias) {
            $key = mb_strtolower(trim($alias));
            if (array_key_exists($key, $headerMap)) {
                return $row[$headerMap[$key]] ?? null;
            }
        }

        return $row[$fallbackIndex] ?? null;
    }

    private function logImportAudit(string $module, int $totalRows, int $imported, array $errors): void
    {
        Log::info('HELOS import audit', [
            'module' => $module,
            'total_rows' => $totalRows,
            'imported_rows' => $imported,
            'failed_rows' => count($errors),
            'sample_errors' => array_slice($errors, 0, 10),
            'user_id' => Auth::id(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    private function getRowsFromUpload(string $field, string $missingMessage): ?array
    {
        $data = $this->form->getState();
        $fileState = $data[$field] ?? null;

        if (empty($fileState)) {
            Notification::make()->title($missingMessage)->danger()->send();
            return null;
        }

        $storedPath = is_array($fileState) ? (array_values($fileState)[0] ?? null) : $fileState;

        if (! $storedPath || ! Storage::disk('public')->exists($storedPath)) {
            Notification::make()->title('Uploaded file could not be found. Please upload again.')->danger()->send();
            return null;
        }

        return Excel::toArray([], Storage::disk('public')->path($storedPath))[0] ?? [];
    }

    private function isRowEmpty(array $row): bool
    {
        return count(array_filter($row, fn ($value) => trim((string) $value) !== '')) === 0;
    }

    private function notifyImportResult(string $successTitle, array $errors): void
    {
        if (! empty($errors)) {
            Notification::make()
                ->title($successTitle . ' Failed: ' . count($errors) . '.')
                ->body(implode("\n", array_slice($errors, 0, 8)))
                ->warning()
                ->send();

            return;
        }

        Notification::make()->title($successTitle)->success()->send();
    }
}
