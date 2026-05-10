<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChannelResource\Pages;
use App\Filament\Resources\ChannelResource\RelationManagers;
use App\Models\Channel;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ChannelResource extends Resource
{
    protected static ?string $model = Channel::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

     protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->role === 1;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->role === 1;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 1;
    }

    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             //
    //         ]);
    // }
public static function form(Form $form): Form
{
    return $form
        ->schema([

            Forms\Components\TextInput::make('name')
                ->label('Channel Name')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, $set) {
                    $set('code', strtoupper(Str::slug($state, '_')));
                }),

            Forms\Components\TextInput::make('code')
                ->required()
                ->disabled(),

        ]);
}
    // public static function table(Table $table): Table
    // {
    //     return $table
    //         ->columns([
    //             //
    //         ])
    //         ->filters([
    //             //
    //         ])
    //         ->actions([
    //             Tables\Actions\EditAction::make(),
    //         ])
    //         ->bulkActions([
    //             Tables\Actions\DeleteBulkAction::make(),
    //         ]);
    // }
    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),

            Tables\Columns\TextColumn::make('name')
                ->label('Channel Name')
                ->searchable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Created At')
                ->dateTime(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ]);
}
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChannels::route('/'),
            'create' => Pages\CreateChannel::route('/create'),
            'edit' => Pages\EditChannel::route('/{record}/edit'),
        ];
    }    
}
