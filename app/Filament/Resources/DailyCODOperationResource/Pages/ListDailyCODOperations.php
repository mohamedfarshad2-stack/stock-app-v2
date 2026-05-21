<?php
namespace App\Filament\Resources\DailyCODOperationResource\Pages;
use App\Exports\HELOSCODOperationsTemplateExport;
use App\Filament\Resources\DailyCODOperationResource;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListDailyCODOperations extends ListRecords
{
    protected static string $resource = DailyCODOperationResource::class;

    protected function getTitle(): string
    {
        return 'Advanced COD Profitability Tracking';
    }

    protected function getSubheading(): ?string
    {
        return 'Use this advanced workspace for expected vs. actual COD performance analysis, not routine daily order entry.';
    }

    protected function getActions(): array
    {
        return [
            Action::make('download_sample')
                ->label('Download Sample')
                ->icon('heroicon-o-download')
                ->action(fn () => Excel::download(new HELOSCODOperationsTemplateExport(), 'cod-operations-sample.xlsx')),
            CreateAction::make()->label('Add Operation Batch'),
        ];
    }
}
