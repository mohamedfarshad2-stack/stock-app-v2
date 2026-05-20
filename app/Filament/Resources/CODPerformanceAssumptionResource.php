<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CODPerformanceAssumptionResource\Pages;
use App\Models\BusinessUnit;
use App\Models\CODPerformanceAssumption;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class CODPerformanceAssumptionResource extends Resource
{
    protected static ?string $model = CODPerformanceAssumption::class;

    protected static ?string $navigationGroup = 'Advanced';

    protected static ?string $navigationLabel = 'COD Assumptions';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public static function shouldRegisterNavigation(): bool
    {
        // Keep visible when COD/hybrid BU exists,
        // or when existing assumption records are present.
        return BusinessUnit::query()->codCompatible()->exists()
            || static::getModel()::query()->exists();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Placeholder::make('info')
                ->content(
                    'This module stores historical COD performance assumptions based on previous operational results. These values help estimate expected courier costs and expected return percentages for forecasting purposes.'
                ),

            Forms\Components\Select::make('business_unit_id')
                ->label('Business Unit')
                ->options(
                    BusinessUnit::query()->codCompatible()->exists()
                        ? BusinessUnit::query()
                            ->codCompatible()
                            ->pluck('name', 'id')
                        : BusinessUnit::query()
                            ->pluck('name', 'id')
                )
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('delivery_charge')
                ->numeric()
                ->prefix('Rs')
                ->required()
                ->default(350)
                ->helperText(
                    'Average courier charge for successful delivery.'
                ),

            Forms\Components\TextInput::make('return_charge')
                ->numeric()
                ->prefix('Rs')
                ->required()
                ->default(175)
                ->helperText(
                    'Average courier charge for returned parcels.'
                ),

            Forms\Components\TextInput::make('expected_return_percentage')
                ->numeric()
                ->suffix('%')
                ->required()
                ->default(30)
                ->helperText(
                    'Estimated return percentage based on previous month performance.'
                ),

            Forms\Components\Textarea::make('notes'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([

            Tables\Columns\TextColumn::make('businessUnit.name')
                ->label('Business Unit')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('delivery_charge')
                ->money('LKR')
                ->label('Delivery Charge'),

            Tables\Columns\TextColumn::make('return_charge')
                ->money('LKR')
                ->label('Return Charge'),

            Tables\Columns\TextColumn::make('expected_return_percentage')
                ->suffix('%')
                ->label('Expected Return %'),

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),

        ])->filters([

            Tables\Filters\SelectFilter::make('business_unit_id')
                ->label('Business Unit')
                ->options(
                    BusinessUnit::query()->codCompatible()->exists()
                        ? BusinessUnit::query()
                            ->codCompatible()
                            ->pluck('name', 'id')
                        : BusinessUnit::query()
                            ->pluck('name', 'id')
                ),

        ])->actions([
            Tables\Actions\EditAction::make(),
        ])->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCODPerformanceAssumptions::route('/'),
            'create' => Pages\CreateCODPerformanceAssumption::route('/create'),
            'edit' => Pages\EditCODPerformanceAssumption::route('/{record}/edit'),
        ];
    }
}
