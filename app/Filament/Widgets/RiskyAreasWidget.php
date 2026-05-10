<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;

use Illuminate\Database\Eloquent\Builder;

use App\Models\Order;

class RiskyAreasWidget extends BaseWidget
{

    protected static ?string $heading = 'High Risk Areas';

    protected function getTableQuery(): Builder
    {
        return Order::query()
            ->selectRaw("
                MIN(id) as id,
                city,
                COUNT(*) as total_orders,
                SUM(CASE WHEN delivery_status = 'returned' THEN 1 ELSE 0 END) as returned_orders
            ")
            ->groupBy('city')
            ->orderByRaw("SUM(CASE WHEN delivery_status = 'returned' THEN 1 ELSE 0 END) DESC");
    }


    protected function getTableColumns(): array
    {
        return [

            TextColumn::make('city')
                ->label('City'),

            TextColumn::make('total_orders')
                ->label('Orders'),

            TextColumn::make('returned_orders')
                ->label('Returns'),

            TextColumn::make('return_rate')
                ->label('Return %')
                ->getStateUsing(function ($record) {

                    if ($record->total_orders == 0) {
                        return 0;
                    }

                    return round(($record->returned_orders / $record->total_orders) * 100, 2);

                }),

        ];
    }

}