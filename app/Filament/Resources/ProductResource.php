<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\BusinessUnit;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationLabel = 'SKU Cost Master';
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'HELOS';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('business_unit_id')->relationship('businessUnit', 'name')->searchable(),
            Forms\Components\TextInput::make('sku')->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('selling_price')->numeric()->required(),
            Forms\Components\TextInput::make('product_cost')->numeric()->required(),
            Forms\Components\TextInput::make('strap_maker_cost')->numeric()->default(0)->label('Strap Maker Cost'),
            Forms\Components\TextInput::make('stitching_worker_cost')->numeric()->default(0)->label('Stitching Worker Cost'),
            Forms\Components\TextInput::make('finishing_worker_cost')->numeric()->default(0)->label('Finishing Worker Cost'),
            Forms\Components\TextInput::make('expected_courier_cost')->numeric()->label('Courier Cost'),
            Forms\Components\TextInput::make('packaging_cost')->numeric()->default(0),
            Forms\Components\TextInput::make('advertisement_allocation')->numeric()->default(0),
            Forms\Components\TextInput::make('operational_overhead')->numeric()->default(0),
            Forms\Components\TextInput::make('return_loss_estimate')->numeric()->default(0),
            Forms\Components\TextInput::make('weight')->numeric(),
            Forms\Components\Toggle::make('is_active')->default(true),
            Forms\Components\Textarea::make('notes')->rows(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('sku')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('businessUnit.name')->label('Business Unit')->sortable(),
            Tables\Columns\TextColumn::make('selling_price')->money('LKR')->sortable(),
            Tables\Columns\TextColumn::make('product_cost')->money('LKR')->sortable(),
            Tables\Columns\TextColumn::make('strap_maker_cost')->money('LKR')->label('Strap Maker'),
            Tables\Columns\TextColumn::make('stitching_worker_cost')->money('LKR')->label('Stitching Worker'),
            Tables\Columns\TextColumn::make('finishing_worker_cost')->money('LKR')->label('Finishing Worker'),
            Tables\Columns\TextColumn::make('expected_courier_cost')->money('LKR')->label('Courier Cost'),
            Tables\Columns\TextColumn::make('packaging_cost')->money('LKR'),
            Tables\Columns\TextColumn::make('advertisement_allocation')->money('LKR')->label('Ad Allocation'),
            Tables\Columns\TextColumn::make('operational_overhead')->money('LKR')->label('Overhead'),
            Tables\Columns\TextColumn::make('return_loss_estimate')->money('LKR')->label('Return Loss'),
            Tables\Columns\TextColumn::make('expected_parcel_profitability')
                ->label('Expected Parcel Profit')
                ->getStateUsing(fn (Product $record) => $record->expectedParcelProfitability())
                ->money('LKR')
                ->sortable(false),
            Tables\Columns\IconColumn::make('is_active')->boolean()->label('Active'),
        ])->filters([
            Tables\Filters\SelectFilter::make('business_unit_id')->label('Business Unit')->options(BusinessUnit::query()->pluck('name', 'id')),
            Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
        ])->actions([
            Tables\Actions\EditAction::make(),
        ])->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
