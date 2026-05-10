<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class HighRiskOrdersWidget extends BaseWidget
{
    protected static ?string $heading = 'High Risk Orders';

    protected function getTableQuery(): Builder
    {
        return Order::query()
            ->whereIn('risk_level', ['high', 'very_high'])
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id')
                ->label('Order'),

            TextColumn::make('customer.name')
                ->label('Customer'),

            TextColumn::make('city')
                ->label('City'),

            BadgeColumn::make('risk_level')
                ->colors([
                    'warning' => 'high',
                    'danger' => 'very_high',
                ]),

            BadgeColumn::make('recommended_action')
                ->colors([
                    'success' => 'ship_direct',
                    'warning' => 'call_verify',
                    'primary' => 'manual_review',
                    'danger' => 'block_cod',
                ]),
        ];
    }
}
