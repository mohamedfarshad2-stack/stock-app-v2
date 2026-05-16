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
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class HELOSImportCenter extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-upload';
    protected static ?string $navigationGroup = 'HELOS';
    protected static ?string $navigationLabel = 'Import Center';
    protected static ?int $navigationSort = 50;
    protected static ?string $slug = 'helos-import-center';
    protected static string $view = 'filament.pages.helos-import-center';

    public $file;

    public $category_file;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\FileUpload::make('file')
                ->label('Upload Excel File')
                ->disk('public')
                ->directory('helos-imports')
                ->acceptedFileTypes([
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-excel',
                ])
                ->required(),

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
                ->action(function () {
                    return Excel::download(new HELOSMoneyRecordTemplateExport(), 'helos-money-record-template.xlsx');
                }),
            Action::make('downloadCategoryTemplate')
                ->label('Download Finance Category Sample')
                ->icon('heroicon-o-download')
                ->action(function () {
                    return Excel::download(new HELOSFinanceCategoryTemplateExport(), 'helos-finance-category-template.xlsx');
                }),
        ];
    }

    public function import(): void
    {
        $data = $this->form->getState();
        $fileState = $data['file'] ?? null;

        if (empty($fileState)) {
            Notification::make()->title('Please upload a file first.')->danger()->send();
            return;
        }

        $storedPath = is_array($fileState) ? (array_values($fileState)[0] ?? null) : $fileState;

        if (! $storedPath || ! Storage::disk('public')->exists($storedPath)) {
            Notification::make()->title('Uploaded file could not be found. Please upload again.')->danger()->send();
            return;
        }

        $fullPath = Storage::disk('public')->path($storedPath);
        $rows = Excel::toArray([], $fullPath)[0] ?? [];

        if (count($rows) <= 1) {
            Notification::make()->title('Excel file has no data rows.')->danger()->send();
            return;
        }

        $imported = 0;
        $errors = [];

        foreach (array_slice($rows, 1) as $index => $row) {
            $line = $index + 2;

            if (count(array_filter($row, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }

            $businessUnit = BusinessUnit::where('name', trim((string) ($row[1] ?? '')))->first();
            $category = FinanceCategory::where('name', trim((string) ($row[2] ?? '')))->first();

            if (! $businessUnit) {
                $errors[] = "Row {$line}: Business Unit not found.";
                continue;
            }

            if (! $category) {
                $errors[] = "Row {$line}: Category not found.";
                continue;
            }

            $type = strtolower(trim((string) ($row[3] ?? '')));
            if (! in_array($type, ['income', 'expense', 'transfer', 'receivable', 'payable'], true)) {
                $errors[] = "Row {$line}: Type must be income/expense/transfer/receivable/payable.";
                continue;
            }

            $amount = (float) ($row[7] ?? 0);
            if ($amount <= 0) {
                $errors[] = "Row {$line}: Amount must be greater than 0.";
                continue;
            }

            MoneyRecord::create([
                'record_date' => $row[0] ?: now()->toDateString(),
                'business_unit_id' => $businessUnit->id,
                'finance_category_id' => $category->id,
                'user_id' => Auth::id(),
                'type' => $type,
                'amount' => $amount,
                'payment_method' => $row[5] ?? null,
                'reference_no' => $row[10] ?? null,
                'description' => $row[11] ?? null,
                'status' => 'approved',
            ]);

            $imported++;
        }

        $summary = "Imported {$imported} rows.";

        if (! empty($errors)) {
            $summary .= ' Failed: ' . count($errors) . '.';
            Notification::make()->title($summary)->body(implode("\n", array_slice($errors, 0, 8)))->warning()->send();
        } else {
            Notification::make()->title($summary)->success()->send();
        }

        $this->form->fill(['file' => null]);
    }


    public function importCategories(): void
    {
        $data = $this->form->getState();
        $fileState = $data['category_file'] ?? null;

        if (empty($fileState)) {
            Notification::make()->title('Please upload a category file first.')->danger()->send();
            return;
        }

        $storedPath = is_array($fileState) ? (array_values($fileState)[0] ?? null) : $fileState;

        if (! $storedPath || ! Storage::disk('public')->exists($storedPath)) {
            Notification::make()->title('Uploaded category file could not be found. Please upload again.')->danger()->send();
            return;
        }

        $rows = Excel::toArray([], Storage::disk('public')->path($storedPath))[0] ?? [];

        if (count($rows) <= 1) {
            Notification::make()->title('Category Excel file has no data rows.')->danger()->send();
            return;
        }

        $imported = 0;
        $errors = [];

        foreach (array_slice($rows, 1) as $index => $row) {
            $line = $index + 2;
            if (count(array_filter($row, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }

            $name = trim((string) ($row[0] ?? ''));
            $type = strtolower(trim((string) ($row[2] ?? '')));
            $parentName = trim((string) ($row[3] ?? ''));
            $isActive = in_array((string) ($row[4] ?? '1'), ['1', 'true', 'TRUE', 'yes', 'YES'], true);

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
                $parent = FinanceCategory::where('name', $parentName)->first();
                if (! $parent) {
                    $errors[] = "Row {$line}: Parent Category not found.";
                    continue;
                }
                $parentId = $parent->id;
            }

            FinanceCategory::updateOrCreate(
                ['name' => $name, 'type' => $type],
                [
                    'code' => trim((string) ($row[1] ?? '')) ?: null,
                    'parent_id' => $parentId,
                    'is_active' => $isActive,
                ]
            );

            $imported++;
        }

        $summary = "Imported {$imported} categories.";

        if (! empty($errors)) {
            $summary .= ' Failed: ' . count($errors) . '.';
            Notification::make()->title($summary)->body(implode("\n", array_slice($errors, 0, 8)))->warning()->send();
        } else {
            Notification::make()->title($summary)->success()->send();
        }

        $this->form->fill(['category_file' => null]);
    }

}
