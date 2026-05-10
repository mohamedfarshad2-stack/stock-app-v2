<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

use App\Services\CODProfitService;

class CODProfitWidget extends StatsOverviewWidget
{

    protected function getCards(): array
    {

        $stats = (new CODProfitService())->getStats();

        return [

            Card::make('Total Orders', $stats['orders']),

            Card::make('Delivered', $stats['delivered']),

            Card::make('Returned', $stats['returned']),

            Card::make('Courier Cost', 'Rs '.$stats['courier_cost']),

            Card::make('Return Loss', 'Rs '.$stats['return_loss']),

        ];

    }

}