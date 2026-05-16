<?php

namespace App\Filament\Resources\MoneyRecordResource\Pages;

use App\Filament\Pages\HELOSImportCenter;
use App\Filament\Resources\MoneyRecordResource;
 codex/create-helos-finance-module-foundation-98xta3

use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\CreateAction;
 main
use Filament\Resources\Pages\ListRecords;

class ListMoneyRecords extends ListRecords
{
    protected static string $resource = MoneyRecordResource::class;

    protected function getActions(): array
    {
        return [
 codex/create-helos-finance-module-foundation-98xta3
            \Filament\Pages\Actions\Action::make('import_excel')->label('Import Excel')->icon('heroicon-o-upload')->url(HELOSImportCenter::getUrl()),
            \Filament\Pages\Actions\CreateAction::make(),
        ];
    }
}

            Action::make('import_excel')
                ->label('Import Excel')
                ->icon('heroicon-o-upload')
                ->url(HELOSImportCenter::getUrl()),

            CreateAction::make(),
        ];
    }
}
 main
