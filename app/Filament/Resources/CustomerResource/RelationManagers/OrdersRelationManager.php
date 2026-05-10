<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $recordTitleAttribute = 'id';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id')
                    ->label('Order ID')
                    ->sortable(),

                TextColumn::make('channel.name')
                    ->label('Channel'),

                TextColumn::make('total_amount')
                    ->label('Amount'),

                BadgeColumn::make('risk_level')
                    ->label('Risk')
                    ->colors([
                        'success' => 'low',
                        'primary' => 'medium',
                        'warning' => 'high',
                        'danger' => 'very_high',
                    ]),

                BadgeColumn::make('delivery_status')
                    ->label('Status')
                    ->colors([
                        'primary' => 'pending',
                        'warning' => 'shipped',
                        'success' => 'delivered',
                        'danger' => 'returned',
                    ]),

                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}