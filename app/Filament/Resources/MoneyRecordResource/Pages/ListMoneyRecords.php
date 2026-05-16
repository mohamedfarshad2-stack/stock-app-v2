<?php

namespace App\Filament\Resources\MoneyRecordResource\Pages;

use App\Filament\Pages\HELOSImportCenter;
use App\Exports\HELOSMoneyRecordTemplateExport;
use App\Filament\Resources\MoneyRecordResource;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListMoneyRecords extends ListRecords
{
    protected static string $resource = MoneyRecordResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('import_excel')
                ->label('Import Transactions')
                ->icon('heroicon-o-upload')
                ->url(HELOSImportCenter::getUrl()),
            Action::make('download_sample')
                ->label('Download Sample')
                ->icon('heroicon-o-download')
                ->action(fn () => Excel::download(new HELOSMoneyRecordTemplateExport(), 'money-records-sample.xlsx')),
            CreateAction::make(),
        ];
    }
}
