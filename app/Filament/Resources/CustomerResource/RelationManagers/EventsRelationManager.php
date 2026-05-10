<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $recordTitleAttribute = 'event_type';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('event_type')
                    ->label('Event'),

                TextColumn::make('notes')
                    ->label('Notes'),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime(),

            ])
            ->defaultSort('created_at', 'desc');
    }
}