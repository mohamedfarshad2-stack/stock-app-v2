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
use App\Models\OrderItem;
use Carbon\Carbon;
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
    protected static ?string $navigationGroup = 'HELOS Core';
    protected static ?string $navigationLabel = 'Import Center';
    protected static bool $shouldRegisterNavigation = true;
    protected static ?int $navigationSort = 5;
    protected static ?string $slug = 'helos-import-center';
    protected static string $view = 'filament.pages.helos-import-center';

    public $file;

    public $import_business_unit_id;

    public $import_profile = 'operations';

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('import_profile')
                ->label('Import Type')
                ->options([
                    'operations' => 'Orders (Operational Intake)',
                    'finance' => 'Money Records',
                    'categories' => 'Cost Categories',
                ])
                ->default('operations')
                ->required()
                ->reactive()
                ->helperText('Choose one import type at a time to keep uploads simple and clear.'),

            Forms\Components\FileUpload::make('file')
                ->label(fn (callable $get) => match ((string) $get('import_profile')) {
                    'finance' => 'Upload Money Records Excel File',
                    'categories' => 'Upload Cost Categories Excel File',
                    default => 'Upload Orders Excel File',
                })
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
                ->visible(fn (callable $get) => (string) $get('import_profile') === 'operations')
                ->helperText('Optional: used as default context for adaptive import routing and validation.'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadMoneyTemplate')
                ->label('Download Money Records Sample')
                ->icon('heroicon-o-download')
                ->action(fn () => Excel::download(
                    new HELOSMoneyRecordTemplateExport(),
                    'helos-money-record-template.xlsx'
                )),

            Action::make('downloadCategoryTemplate')
                ->label('Download Cost Category Sample')
                ->icon('heroicon-o-download')
                ->action(fn () => Excel::download(
                    new HELOSFinanceCategoryTemplateExport(),
                    'helos-cost-category-template.xlsx'
                )),

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

    public function downloadSample()
    {
        $state = $this->form->getState();

        $profile = (string) ($state['import_profile'] ?? 'operations');

        if ($profile === 'finance') {
            return Excel::download(
                new HELOSMoneyRecordTemplateExport(),
                'helos-money-record-template.xlsx'
            );
        }

        if ($profile === 'categories') {
            return Excel::download(
                new HELOSFinanceCategoryTemplateExport(),
                'helos-cost-category-template.xlsx'
            );
        }

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
    }

    public function getSampleButtonLabelProperty(): string
    {
        $profile = (string) ($this->form->getState()['import_profile'] ?? 'operations');

        return match ($profile) {
            'finance' => 'Download Money Records Sample',
            'categories' => 'Download Cost Categories Sample',
            default => 'Download Orders Sample',
        };
    }

    public function import(): void
    {
        $context = $this->getImportContext();

        $rows = $this->getRowsFromUpload(
            'file',
            'Please upload an Excel file first.'
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

        if (($context['profile'] ?? '') === 'operations') {
            $this->importOperationalRows($rows, $context);

            $this->form->fill([
                'file' => null,
                'import_profile' => $context['profile'],
                'import_business_unit_id' => $context['business_unit_id'],
            ]);

            return;
        }

        if (($context['profile'] ?? '') === 'categories') {
            $this->importCategoriesFromRows($rows, $context);

            $this->form->fill([
                'file' => null,
                'import_profile' => $context['profile'],
                'import_business_unit_id' => $context['business_unit_id'],
            ]);

            return;
        }

        $businessUnitMap = BusinessUnit::query()
            ->pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [
                mb_strtolower(trim((string) $name)) => $id,
            ]);

        $categoryMap = FinanceCategory::query()
            ->pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [
                mb_strtolower(trim((string) $name)) => $id,
            ]);

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

            if (! in_array($type, [
                'income',
                'expense',
                'transfer',
                'receivable',
                'payable',
            ], true)) {

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

    private function importOperationalRows(array $rows, array $context): void
    {
        $headerMap = $this->buildHeaderMap($rows[0] ?? []);

        $accepted = 0;
        $skipped = 0;
        $failed = 0;

        $errors = [];

        $channelMap = Channel::query()
            ->get()
            ->keyBy(fn (Channel $channel) => mb_strtolower(trim((string) $channel->name)));

        $defaultChannel = Channel::query()
            ->orderBy('id')
            ->first();

        if (! $defaultChannel) {

            Notification::make()
                ->title('Operational intake requires at least one channel. Please create a channel first.')
                ->danger()
                ->send();

            $this->logImportAudit(
                'operational_intake_preview',
                count($rows) - 1,
                0,
                ['No channel found for order persistence.'],
                $context
            );

            return;
        }

        foreach (array_slice($rows, 1) as $index => $row) {

            $line = $index + 2;

            if ($this->isRowEmpty($row)) {
                $skipped++;
                continue;
            }

            $customerName = trim((string) $this->getMappedValue(
                $row,
                $headerMap,
                ['customer', 'customer name', 'name'],
                2
            ));

            $orderNo = trim(
                (string) $this->getMappedValue(
                    $row,
                    $headerMap,
                    ['order no', 'order number', 'order_no', 'order id'],
                    0
                )
            );

            $phone = $this->normalizePhoneValue((string) $this->getMappedValue(
                $row,
                $headerMap,
                ['phone', 'mobile', 'customer phone'],
                3
            ));

            $orderDateRaw = trim(
                (string) $this->getMappedValue(
                    $row,
                    $headerMap,
                    ['order date', 'date', 'order_date'],
                    1
                )
            );

            $rawStatus = (string) $this->getMappedValue(
                $row,
                $headerMap,
                ['operational status', 'status', 'delivery state', 'csr status'],
                8
            );

            $confirmationStatus = trim((string) $this->getMappedValue(
                $row,
                $headerMap,
                ['confirmation state', 'verification status', 'confirmation'],
                13
            ));

            if (trim($rawStatus) === '') {
                $rawStatus = 'pending';
            }

            $normalizedStatus = $this->normalizeOperationalStatusValue(
                $rawStatus,
                $context['status_preset'] ?? []
            );

            $codAmount = (float) $this->getMappedValue(
                $row,
                $headerMap,
                ['cod amount', 'amount', 'total amount'],
                6
            );

            $businessUnitName = trim((string) $this->getMappedValue(
                $row,
                $headerMap,
                ['business unit', 'business_unit', 'workspace_or_business_unit'],
                15
            ));

            $channelName = trim((string) $this->getMappedValue(
                $row,
                $headerMap,
                ['service/channel', 'channel', 'service_or_pipeline'],
                14
            ));

            $resolvedChannel = $channelMap[mb_strtolower($channelName)] ?? $defaultChannel;

            if ($customerName === '' && $phone === '') {
                $errors[] = "Row {$line}: Order No or Phone is required.";
                $failed++;
                continue;
            }

            if ($normalizedStatus === null) {
                $normalizedStatus = 'pending';
            }

            $orderDate = $this->parseDateOrNull($orderDateRaw);
            if ($orderDate === null) {
                $orderDate = now()->toDateTimeString();
            }

            $customer = Customer::firstOrCreate(
                [
                    'normalized_phone' => Customer::normalizePhone(
                        $phone !== '' ? $phone : '0000000000'
                    ),
                ],
                [
                    'name' => $customerName ?: 'Unknown Customer',

                    'phone' => $phone !== ''
                        ? $phone
                        : '0000000000',

                    'address' => trim(
                        (string) $this->getMappedValue(
                            $row,
                            $headerMap,
                            ['address'],
                            4
                        )
                    ) ?: null,

                    'city' => trim(
                        (string) $this->getMappedValue(
                            $row,
                            $headerMap,
                            ['city'],
                            4
                        )
                    ) ?: null,

                    'user_id' => Auth::id(),
                ]
            );

            $orderQuery = Order::query()
                ->where('customer_id', $customer->id)
                ->where('channel_id', $resolvedChannel->id);

            if ($orderNo !== '') {

                $orderQuery->where(
                    'external_order_no',
                    $orderNo
                );

            } else {

                $orderQuery
                    ->whereDate(
                        'order_date',
                        now()->parse($orderDate)->toDateString()
                    )
                    ->where(
                        'total_amount',
                        $codAmount
                    );
            }

            if ($orderQuery->exists()) {
                $errors[] = "Row {$line}: Duplicate operational order detected.";
                $failed++;
                continue;
            }

            $order = Order::create([
                'customer_id' => $customer->id,
                'channel_id' => $resolvedChannel->id,
                'external_order_no' => $orderNo ?: null,
                'source' => 'manual',
                'channel_reference' => $channelName !== '' ? $channelName : null,
                'order_date' => $orderDate,
                'total_amount' => $codAmount,
                'payment_method' => 'cod',

                'courier_name' => trim(
                    (string) $this->getMappedValue(
                        $row,
                        $headerMap,
                        ['courier', 'courier name'],
                        11
                    )
                ) ?: null,

                'address' => trim(
                    (string) $this->getMappedValue(
                        $row,
                        $headerMap,
                        ['address'],
                        4
                    )
                ) ?: null,

                'city' => trim(
                    (string) $this->getMappedValue(
                        $row,
                        $headerMap,
                        ['city'],
                        4
                    )
                ) ?: null,

                'verification_status' => $confirmationStatus !== ''
                    ? $confirmationStatus
                    : 'pending',

                'delivery_status' => $normalizedStatus,

                'return_reason' => trim(
                    (string) $this->getMappedValue(
                        $row,
                        $headerMap,
                        ['failure reason', 'cancellation reason', 'return reason'],
                        9
                    )
                ) ?: null,

                'risk_score' => 0,
                'risk_level' => 'new',
                'user_id' => Auth::id(),
            ]);

            $productText = trim((string) $this->getMappedValue(
                $row,
                $headerMap,
                ['products', 'product text', 'product', 'sku'],
                5
            ));

            $parsedItem = $this->parseOperationalProductText($productText);

            if ($productText !== '') {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_name' => $parsedItem['name'],
                    'sku' => $parsedItem['sku'],
                    'quantity' => $parsedItem['quantity'],
                    'unit_price' => $parsedItem['quantity'] > 0
                        ? round($codAmount / $parsedItem['quantity'], 2)
                        : $codAmount,
                    'total_price' => $codAmount,
                ]);
            }

            $accepted++;
        }

        $this->logImportAudit(
            'operational_intake',
            count($rows) - 1,
            $accepted,
            $errors,
            array_merge($context, [
                'summary' => compact('accepted', 'skipped', 'failed'),
            ])
        );

        Notification::make()
            ->title("Operational intake processed: {$accepted} imported")
            ->body(
                "Total: " . (count($rows) - 1)
                . " | Imported: {$accepted}"
                . " | Skipped: {$skipped}"
                . " | Failed: {$failed}"
                . ($errors === [] ? '' : "\n" . implode("\n", array_slice($errors, 0, 8)))
            )
            ->success($errors === [])
            ->warning($errors !== [])
            ->send();
    }

    private function importCategoriesFromRows(array $rows, array $context): void
    {
        if (count($rows) <= 1) {

            Notification::make()
                ->title('Category Excel file has no data rows.')
                ->danger()
                ->send();

            return;
        }

        $categoryIdMap = FinanceCategory::query()
            ->pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [
                mb_strtolower(trim((string) $name)) => $id,
            ]);

        $imported = 0;

        $errors = [];

        $headerMap = $this->buildHeaderMap($rows[0] ?? []);

        foreach (array_slice($rows, 1) as $index => $row) {

            $line = $index + 2;

            if ($this->isRowEmpty($row)) {
                continue;
            }

            $name = trim(
                (string) $this->getMappedValue(
                    $row,
                    $headerMap,
                    ['name', 'category'],
                    0
                )
            );

            $type = strtolower(
                trim(
                    (string) $this->getMappedValue(
                        $row,
                        $headerMap,
                        ['type'],
                        2
                    )
                )
            );

            $parentName = trim(
                (string) $this->getMappedValue(
                    $row,
                    $headerMap,
                    ['parent', 'parent category', 'parent_id'],
                    3
                )
            );

            $isActive = in_array(
                strtolower(
                    (string) $this->getMappedValue(
                        $row,
                        $headerMap,
                        ['active', 'is_active'],
                        4
                    )
                ),
                ['1', 'true', 'yes'],
                true
            );

            if ($name === '') {
                $errors[] = "Row {$line}: Name is required.";
                continue;
            }

            if (! in_array($type, [
                'income',
                'expense',
                'transfer',
                'receivable',
                'payable',
            ], true)) {

                $errors[] = "Row {$line}: Type must be income/expense/transfer/receivable/payable.";

                continue;
            }

            $parentId = null;

            if ($parentName !== '') {

                $parentId = $categoryIdMap[
                    mb_strtolower($parentName)
                ] ?? null;

                if (! $parentId) {
                    $errors[] = "Row {$line}: Parent Category not found.";
                    continue;
                }
            }

            FinanceCategory::updateOrCreate(
                [
                    'name' => $name,
                    'type' => $type,
                ],
                [
                    'code' => trim(
                        (string) $this->getMappedValue(
                            $row,
                            $headerMap,
                            ['code'],
                            1
                        )
                    ) ?: null,

                    'parent_id' => $parentId,

                    'is_active' => $isActive,
                ]
            );

            $imported++;
        }

        $this->logImportAudit(
            'finance_categories',
            count($rows) - 1,
            $imported,
            $errors,
            $context
        );

        $this->notifyImportResult(
            "Imported {$imported} categories.",
            $errors
        );
    }

    private function getImportContext(): array
    {
        $data = $this->form->getState();

        $profile = (string) ($data['import_profile'] ?? 'operations');

        $buId = $data['import_business_unit_id'] ?? null;

        $businessType = 'general';

        if (! empty($buId)) {
            $businessType = (string) (
                BusinessUnit::query()
                    ->find($buId)
                    ?->type ?? 'general'
            );
        }

        return [
            'profile' => $profile,
            'business_unit_id' => $buId,
            'business_type' => $businessType,
            'status_preset' => $this->getOperationalStatusPreset($businessType),
        ];
    }

    private function getOperationalStatusPreset(string $businessType): array
    {
        $normalized = [
            'pending' => ['pending', 'new', 'queue', 'queued', 'hold'],
            'delivered' => ['delivered', 'done', 'success', 'completed'],
            'returned' => ['returned', 'return', 'rto', 'reverse'],
            'cancelled' => ['cancelled', 'canceled', 'cancel', 'rejected'],
        ];

        if ($businessType === 'cod') {
            $normalized['pending'][] = 'no_answer';
            $normalized['returned'][] = 'refused';
            $normalized['cancelled'][] = 'fake_order';
        }

        return $normalized;
    }

    private function normalizeOperationalStatusValue(string $raw, array $preset): ?string
    {
        $value = mb_strtolower(trim($raw));

        if ($value === '') {
            return null;
        }

        foreach ($preset as $normalized => $aliases) {
            if (in_array($value, $aliases, true)) {
                return $normalized;
            }
        }

        return null;
    }

    private function buildHeaderMap(array $headerRow): array
    {
        $map = [];

        foreach ($headerRow as $i => $header) {

            $normalized = mb_strtolower(
                trim((string) $header)
            );

            if ($normalized !== '') {
                $map[$normalized] = $i;
            }
        }

        return $map;
    }

    private function parseOperationalProductText(string $raw): array
    {
        $value = trim($raw);

        if ($value === '') {
            return [
                'name' => 'Unknown Item',
                'sku' => null,
                'quantity' => 1,
            ];
        }

        $quantity = 1;

        if (preg_match('/(?:x|\*)\s*(\d+)$/i', $value, $matches) === 1) {
            $quantity = max(1, (int) $matches[1]);
            $value = trim((string) preg_replace('/(?:x|\*)\s*\d+$/i', '', $value));
        }

        $sku = null;

        if (preg_match('/([A-Za-z0-9]+(?:[-_\/][A-Za-z0-9]+)+)/', $value, $matches) === 1) {
            $sku = mb_strtoupper(trim($matches[1]));
        } elseif (preg_match('/^[A-Za-z0-9]{4,}$/', $value) === 1) {
            $sku = mb_strtoupper($value);
        }

        return [
            'name' => $value,
            'sku' => $sku,
            'quantity' => $quantity,
        ];
    }

    private function normalizePhoneValue(string $raw): string
    {
        $digits = preg_replace('/\D+/', '', $raw) ?? '';

        if (str_starts_with($digits, '94') && strlen($digits) === 11) {
            return '0' . substr($digits, 2);
        }

        return $digits;
    }

    private function parseDateOrNull(string $raw): ?string
    {
        $value = trim($raw);

        if ($value === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->toDateTimeString();
        } catch (\Throwable) {
            return null;
        }
    }

    private function getMappedValue(
        array $row,
        array $headerMap,
        array $aliases,
        int $fallbackIndex
    ): mixed {
        foreach ($aliases as $alias) {

            $key = mb_strtolower(trim($alias));

            if (array_key_exists($key, $headerMap)) {
                return $row[$headerMap[$key]] ?? null;
            }
        }

        return $row[$fallbackIndex] ?? null;
    }

    private function logImportAudit(
        string $module,
        int $totalRows,
        int $imported,
        array $errors,
        array $context = []
    ): void {
        Log::info('HELOS import audit', [
            'module' => $module,
            'total_rows' => $totalRows,
            'imported_rows' => $imported,
            'failed_rows' => count($errors),
            'sample_errors' => array_slice($errors, 0, 10),
            'import_profile' => $context['profile'] ?? 'finance',
            'business_unit_id' => $context['business_unit_id'] ?? null,
            'business_type' => $context['business_type'] ?? 'general',
            'status_preset_keys' => array_keys($context['status_preset'] ?? []),
            'summary' => $context['summary'] ?? null,
            'user_id' => Auth::id(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    private function getRowsFromUpload(
        string $field,
        string $missingMessage
    ): ?array {
        $data = $this->form->getState();

        $fileState = $data[$field] ?? null;

        if (empty($fileState)) {

            Notification::make()
                ->title($missingMessage)
                ->danger()
                ->send();

            return null;
        }

        $storedPath = is_array($fileState)
            ? (array_values($fileState)[0] ?? null)
            : $fileState;

        if (
            ! $storedPath ||
            ! Storage::disk('public')->exists($storedPath)
        ) {

            Notification::make()
                ->title('Uploaded file could not be found. Please upload again.')
                ->danger()
                ->send();

            return null;
        }

        return Excel::toArray(
            [],
            Storage::disk('public')->path($storedPath)
        )[0] ?? [];
    }

    private function isRowEmpty(array $row): bool
    {
        return count(
            array_filter(
                $row,
                fn ($value) => trim((string) $value) !== ''
            )
        ) === 0;
    }

    private function notifyImportResult(
        string $successTitle,
        array $errors
    ): void {
        if (! empty($errors)) {

            Notification::make()
                ->title($successTitle . ' Failed: ' . count($errors) . '.')
                ->body(
                    implode(
                        "\n",
                        array_slice($errors, 0, 8)
                    )
                )
                ->warning()
                ->send();

            return;
        }

        Notification::make()
            ->title($successTitle)
            ->success()
            ->send();
    }
}
