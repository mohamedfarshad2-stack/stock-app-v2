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
            Forms\Components\TextInput::make('expected_courier_cost')->numeric()->label('Courier Cost'),
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
            Tables\Columns\TextColumn::make('expected_courier_cost')->money('LKR')->label('Courier Cost'),
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
