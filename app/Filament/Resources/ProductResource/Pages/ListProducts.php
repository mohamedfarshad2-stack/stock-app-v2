<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Exports\HELOSSKUCostMasterTemplateExport;
use App\Filament\Resources\ProductResource;
use App\Models\BusinessUnit;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
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
                ->action(fn () => Excel::download(
                    new HELOSSKUCostMasterTemplateExport(),
                    'sku-cost-master-sample.xlsx'
                )),

            Action::make('import_sku_costs')
                ->label('Import SKU Cost Master')
                ->icon('heroicon-o-upload')
                ->form([
                    FileUpload::make('file')
                        ->required()
                        ->disk('public')
                        ->directory('helos-imports')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ]),
                ])
                ->action(function (array $data): void {
                    $storedPath = $data['file'] ?? null;

                    if (! $storedPath || ! Storage::disk('public')->exists($storedPath)) {
                        Notification::make()
                            ->title('Uploaded file not found.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $rows = Excel::toArray(
                        [],
                        Storage::disk('public')->path($storedPath)
                    )[0] ?? [];

                    if (count($rows) <= 1) {
                        Notification::make()
                            ->title('Excel has no data rows.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $buMap = BusinessUnit::query()
                        ->pluck('id', 'name')
                        ->mapWithKeys(fn ($id, $name) => [
                            mb_strtolower(trim((string) $name)) => $id,
                        ]);

                    $imported = 0;

                    $errors = [];

                    foreach (array_slice($rows, 1) as $i => $row) {
                        $line = $i + 2;

                        $sku = trim((string) ($row[1] ?? ''));

                        $name = trim((string) ($row[2] ?? ''));

                        if ($sku === '' || $name === '') {
                            $errors[] = "Row {$line}: SKU and Product Name are required.";

                            continue;
                        }

                        $buId = $buMap[
                            mb_strtolower(trim((string) ($row[0] ?? '')))
                        ] ?? null;

                        $strap = (float) ($row[5] ?? 0);

                        $stitch = (float) ($row[6] ?? 0);

                        $stitchEmp = (float) ($row[7] ?? 0);

                        $upper = (float) ($row[8] ?? 0);

                        $middle = (float) ($row[9] ?? 0);

                        $bottom = (float) ($row[10] ?? 0);

                        $glue = (float) ($row[11] ?? 0);

                        $stationery = (float) ($row[12] ?? 0);

                        $baseEmp = (float) ($row[13] ?? 0);

                        $packing = (float) ($row[14] ?? 0);

                        $courier = (float) ($row[15] ?? 0);

                        $adAlloc = (float) ($row[16] ?? 0);

                        $returnLoss = (float) ($row[17] ?? 0);

                        $manufacturing = $strap
                            + $stitch
                            + $stitchEmp
                            + $upper
                            + $middle
                            + $bottom
                            + $glue;

                        $overhead = $stationery + $baseEmp;

                        Product::updateOrCreate(
                            ['sku' => $sku],
                            [
                                'business_unit_id' => $buId,
                                'name' => $name,
                                'selling_price' => (float) ($row[4] ?? 0),
                                'product_cost' => $manufacturing,
                                'expected_courier_cost' => $courier,
                                'packaging_cost' => $packing,
                                'advertisement_allocation' => $adAlloc,
                                'operational_overhead' => $overhead,
                                'return_loss_estimate' => $returnLoss,
                                'weight' => (float) ($row[18] ?? 0),
                                'is_active' => in_array(
                                    strtolower((string) ($row[19] ?? '1')),
                                    ['1', 'true', 'yes'],
                                    true
                                ),
                                'notes' => trim((string) ($row[20] ?? '')),
                            ]
                        );

                        $imported++;
                    }

                    Notification::make()
                        ->title("Imported {$imported} SKU rows")
                        ->body(
                            $errors === []
                                ? 'Completed successfully.'
                                : implode("\n", array_slice($errors, 0, 10))
                        )
                        ->success($errors === [])
                        ->danger($errors !== [])
                        ->send();
                }),

            CreateAction::make(),
        ];
    }
}