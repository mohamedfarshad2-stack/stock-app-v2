<?php

namespace App\Filament\Resources\MoneyRecordResource\Pages;

use App\Filament\Resources\MoneyRecordResource;
use Filament\Resources\Pages\ListRecords;

class ListMoneyRecords extends ListRecords
{
    protected static string  = MoneyRecordResource::class;

    protected function getActions(): array
    {
        return [
            \Filament\Pages\Actions\CreateAction::make(),
        ];
    }
}
