<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CODAssumptionResource\Pages;
use App\Models\CODAssumption;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class CODAssumptionResource extends Resource
{
    protected static ?string $model = CODAssumption::class;
    protected static ?string $navigationGroup = 'HELOS';
    protected static ?string $navigationLabel = 'COD Forecast Assumptions';
    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('business_unit_id')->relationship('businessUnit', 'name')->required(),
            Forms\Components\TextInput::make('expected_return_percentage')->numeric()->required(),
            Forms\Components\TextInput::make('expected_return_courier_cost')->numeric()->required(),
            Forms\Components\TextInput::make('expected_recovery_percentage')->numeric()->required(),
            Forms\Components\TextInput::make('expected_recovery_cost')->numeric()->required(),
            Forms\Components\TextInput::make('default_cod_margin_percentage')->numeric(),
            Forms\Components\Placeholder::make('analytics_scope')
                ->content('This module is for forecasting assumptions only. Operational order entry happens in Daily COD Operations.'),
            Forms\Components\Textarea::make('notes'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('businessUnit.name')->label('Business Unit')->sortable(),
            Tables\Columns\TextColumn::make('expected_return_percentage')->suffix('%'),
            Tables\Columns\TextColumn::make('expected_return_courier_cost')->money('LKR'),
            Tables\Columns\TextColumn::make('expected_recovery_percentage')->suffix('%'),
            Tables\Columns\TextColumn::make('expected_recovery_cost')->money('LKR'),
            Tables\Columns\TextColumn::make('default_cod_margin_percentage')->suffix('%'),
            Tables\Columns\TextColumn::make('expected_success_percentage')
                ->label('Expected Success %')
                ->getStateUsing(fn (CODAssumption $record) => round(100 - (float) $record->expected_return_percentage, 2).'%'),
        ])->actions([
            Tables\Actions\EditAction::make(),
        ])->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCODAssumptions::route('/'),
            'create' => Pages\CreateCODAssumption::route('/create'),
            'edit' => Pages\EditCODAssumption::route('/{record}/edit'),
        ];
    }
}
