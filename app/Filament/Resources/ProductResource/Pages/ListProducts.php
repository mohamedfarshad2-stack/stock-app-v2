<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Exports\HELOSSKUCostMasterTemplateExport;
use App\Filament\Resources\ProductResource;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
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
            CreateAction::make(),
        ];
    }
}
