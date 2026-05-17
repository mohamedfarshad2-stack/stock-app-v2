<?php

namespace App\Filament\Pages;

use App\Exports\HELOSFinanceCategoryTemplateExport;
use App\Exports\HELOSMoneyRecordTemplateExport;
use App\Exports\HELOSOperationalIntakeTemplateExport;
use App\Models\BusinessUnit;
use App\Models\Channel;
use App\Models\Customer;
use App\Models\FinanceCategory;
use App\Models\MoneyRecord;
use App\Models\Order;
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

    public $import_business_unit_id;

    public $import_profile = 'auto';

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

            Forms\Components\Select::make('import_business_unit_id')
                ->label('Business Unit Context')
                ->options(
                    BusinessUnit::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                )
                ->searchable()
                ->helperText(
                    'Optional: used as default context for adaptive import routing and validation.'
                ),

            Forms\Components\Select::make('import_profile')
                ->label('Import Profile')
                ->options([
                    'auto' => 'Auto Detect',
                    'finance' => 'Finance Records',
                    'operations' => 'Operational Intake (Phase 1)',
                ])
                ->default('auto')
                ->helperText(
                    'Keeps current workflow intact. Auto mode uses existing behavior and logs detected profile.'
                ),

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
                ->action(
                    fn () => Excel::download(
                        new HELOSMoneyRecordTemplateExport(),
                        'helos-money-record-template.xlsx'
                    )
                ),

            Action::make('downloadCategoryTemplate')
                ->label('Download Cost Category Sample')
                ->icon('heroicon-o-download')
                ->action(
                    fn () => Excel::download(
                        new HELOSFinanceCategoryTemplateExport(),
                        'helos-cost-category-template.xlsx'
                    )
                ),

            Action::make('downloadOperationalTemplate')
                ->label('Download Operational Intake Sample')
                ->icon('heroicon-o-download')
                ->action(function () {
                    $state = $this->form->getState();

                    $businessType = 'general';

                    if (! empty($state['import_business_unit_id'])) {
                        $businessType = (string) (
                            BusinessUnit::query()
                                ->find($state['import_business_unit_id'])
                                ?->type ?? 'general'
                        );
                    }

                    return Excel::download(
                        new HELOSOperationalIntakeTemplateExport($businessType),
                        'helos-operational-intake-sample.xlsx'
                    );
                }),
        ];
    }

    public function import(): void
    {
        $context = $this->getImportContext();

        $rows = $this->getRowsFromUpload(
            'file',
            'Please upload a money records file first.'
        );

        if ($rows === null) {
            return;
        }

        if (count($rows) <= 1) {
            Notification::make()
                ->title('Excel file has no data rows.')
                ->danger()
                ->send();

            return;
        }

        if (($context['profile'] ?? 'finance') === 'operations') {
            $this->importOperationalRows($rows, $context);

            $this->form->fill([
                'file' => null,
                'import_profile' => $context['profile'],
                'import_business_unit_id' => $context['business_unit_id'],
            ]);

            return;
        }

        $businessUnitMap = BusinessUnit::query()
            ->pluck('id', 'name')
            ->mapWithKeys(
                fn ($id, $name) => [
                    mb_strtolower(trim((string) $name)) => $id,
                ]
            );

        $categoryMap = FinanceCategory::query()
            ->pluck('id', 'name')
            ->mapWithKeys(
                fn ($id, $name) => [
                    mb_strtolower(trim((string) $name)) => $id,
                ]
            );

        $imported = 0;

        $errors = [];

        $headerMap = $this->buildHeaderMap($rows[0] ?? []);

        foreach (array_slice($rows, 1) as $index => $row) {
            $line = $index + 2;

            if ($this->isRowEmpty($row)) {
                continue;
            }

            $businessUnitId = $businessUnitMap[
                mb_strtolower(
                    trim(
                        (string) $this->getMappedValue(
                            $row,
                            $headerMap,
                            ['business unit', 'business_unit'],
                            1
                        )
                    )
                )
            ] ?? null;

            $categoryId = $categoryMap[
                mb_strtolower(
                    trim(
                        (string) $this->getMappedValue(
                            $row,
                            $headerMap,
                            ['category', 'finance category', 'finance_category'],
                            2
                        )
                    )
                )
            ] ?? null;

            if (! $businessUnitId) {
                $errors[] = "Row {$line}: Business Unit not found.";
                continue;
            }

            if (! $categoryId) {
                $errors[] = "Row {$line}: Category not found.";
                continue;
            }

            $type = strtolower(
                trim(
                    (string) $this->getMappedValue(
                        $row,
                        $headerMap,
                        ['type', 'transaction type'],
                        3
                    )
                )
            );

            if (! in_array(
                $type,
                ['income', 'expense', 'transfer', 'receivable', 'payable'],
                true
            )) {
                $errors[] = "Row {$line}: Type must be income/expense/transfer/receivable/payable.";
                continue;
            }

            $amount = (float) $this->getMappedValue(
                $row,
                $headerMap,
                ['amount', 'value'],
                7
            );

            if ($amount <= 0) {
                $errors[] = "Row {$line}: Amount must be greater than 0.";
                continue;
            }

            $recordDate = ! empty(
                $this->getMappedValue(
                    $row,
                    $headerMap,
                    ['record date', 'date', 'record_date'],
                    0
                )
            )
                ? (string) $this->getMappedValue(
                    $row,
                    $headerMap,
                    ['record date', 'date', 'record_date'],
                    0
                )
                : now()->toDateString();

            $referenceNo = $this->getMappedValue(
                $row,
                $headerMap,
                ['reference no', 'reference', 'reference_no'],
                10
            );

            $isDuplicate = MoneyRecord::query()
                ->whereDate('record_date', $recordDate)
                ->where('business_unit_id', $businessUnitId)
                ->where('finance_category_id', $categoryId)
                ->where('type', $type)
                ->where('amount', $amount)
                ->when(
                    $referenceNo,
                    fn ($q) => $q->where('reference_no', $referenceNo)
                )
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
                'payment_method' => $this->getMappedValue(
                    $row,
                    $headerMap,
                    ['payment method', 'payment_method'],
                    5
                ),
                'reference_no' => $referenceNo,
                'description' => $this->getMappedValue(
                    $row,
                    $headerMap,
                    ['description', 'notes'],
                    11
                ),
                'status' => 'approved',
            ]);

            $imported++;
        }

        $this->logImportAudit(
            'money_records',
            count($rows) - 1,
            $imported,
            $errors,
            $context
        );

        $this->notifyImportResult(
            "Imported {$imported} rows.",
            $errors
        );

        $this->form->fill([
            'file' => null,
            'import_profile' => $context['profile'],
            'import_business_unit_id' => $context['business_unit_id'],
        ]);
    }
}