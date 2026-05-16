<?php

namespace App\Filament\Resources\MoneyRecordResource\Pages;

use App\Filament\Pages\HELOSImportCenter;
use App\Filament\Resources\MoneyRecordResource;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMoneyRecords extends ListRecords
{
    protected static string $resource = MoneyRecordResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('import_excel')
                ->label('Import Excel')
                ->icon('heroicon-o-upload')
                ->url(HELOSImportCenter::getUrl()),

            CreateAction::make(),
        ];
    }
}