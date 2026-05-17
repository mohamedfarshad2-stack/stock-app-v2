<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Exports\HELOSSKUCostMasterTemplateExport;
use App\Filament\Resources\ProductResource;
use App\Models\BusinessUnit;
use App\Models\Product;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('download_sku_sample')
                ->label('Download SKU Cost Sample')
                ->icon('heroicon-o-download')
                ->action(fn () => Excel::download(new HELOSSKUCostMasterTemplateExport(), 'sku-cost-master-sample.xlsx')),
            Action::make('import_sku_costs')
                ->label('Import SKU Costs')
                ->icon('heroicon-o-upload')
                ->form([
                    Forms\Components\FileUpload::make('file')
                        ->label('SKU Cost Master File')
                        ->disk('public')
                        ->directory('helos-imports')
                        ->required()
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ]),
                ])
                ->action(function (array $data): void {
                    $fileState = $data['file'] ?? null;
                    $storedPath = is_array($fileState) ? (array_values($fileState)[0] ?? null) : $fileState;

                    if (! $storedPath || ! Storage::disk('public')->exists($storedPath)) {
                        Notification::make()->title('Uploaded file not found. Please upload again.')->danger()->send();
                        return;
                    }

                    $rows = Excel::toArray([], Storage::disk('public')->path($storedPath))[0] ?? [];
                    if (count($rows) <= 1) {
                        Notification::make()->title('SKU file has no data rows.')->warning()->send();
                        return;
                    }

                    $businessUnitMap = BusinessUnit::query()->pluck('id', 'name')
                        ->mapWithKeys(fn ($id, $name) => [mb_strtolower(trim((string) $name)) => $id]);

                    $imported = 0;
                    $errors = [];

                    foreach (array_slice($rows, 1) as $index => $row) {
                        $line = $index + 2;
                        $sku = trim((string) ($row[0] ?? ''));
                        $name = trim((string) ($row[3] ?? ''));

                        if ($sku === '' || $name === '') {
                            $errors[] = "Row {$line}: SKU and Name are required.";
                            continue;
                        }

                        $buName = mb_strtolower(trim((string) ($row[2] ?? '')));
                        $buId = $businessUnitMap[$buName] ?? null;

                        $product = Product::updateOrCreate(
                            ['sku' => $sku],
                            [
                                'item_code' => $sku,
                                'title' => $name,
                                'cost' => (float) ($row[12] ?? 0),
                                'business_unit_id' => $buId,
                                'name' => $name,
                                'selling_price' => (float) ($row[4] ?? 0),
                                'product_cost' => (float) ($row[12] ?? 0),
                                'expected_courier_cost' => (float) ($row[14] ?? 0),
                                'packaging_cost' => (float) ($row[15] ?? 0),
                                'advertisement_allocation' => (float) ($row[16] ?? 0),
                                'operational_overhead' => (float) ($row[10] ?? 0) + (float) ($row[11] ?? 0),
                                'return_loss_estimate' => (float) ($row[17] ?? 0),
                                'weight' => (float) ($row[18] ?? 0),
                                'is_active' => (bool) ((int) ($row[19] ?? 1)),
                                'notes' => (string) ($row[20] ?? ''),
                            ]
                        );

                        if ($product->item_code === null || trim((string) $product->item_code) === '') {
                            $product->item_code = $sku;
                            $product->save();
                        }

                        $imported++;
                    }

                    Notification::make()
                        ->title("SKU import completed: {$imported} rows processed")
                        ->body($errors === [] ? 'All rows imported successfully.' : implode("\n", array_slice($errors, 0, 8)))
                        ->success($errors === [])
                        ->warning($errors !== [])
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
