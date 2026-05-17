<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailyCODOperationResource\Pages;
use App\Models\BusinessUnit;
use App\Models\DailyCODOperation;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class DailyCODOperationResource extends Resource
{
    protected static ?string $model = DailyCODOperation::class;
    protected static ?string $navigationGroup = 'HELOS';
    protected static ?string $navigationLabel = 'Daily COD Operations';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('operation_date')->required(),
            Forms\Components\TextInput::make('order_code')->label('Order Code')->helperText('Optional: external order reference'),
            Forms\Components\Select::make('business_unit_id')->relationship('businessUnit', 'name')->required(),
            Forms\Components\Select::make('product_id')->relationship('product', 'name')->searchable()->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $product = Product::find($state);
                    if (! $product) {
                        return;
                    }

                    $set('selling_price', $product->selling_price);
                    $set('product_cost', $product->product_cost);
                    $set('courier_cost', $product->expected_courier_cost);
                    $set('expected_return_percentage', $product->return_loss_estimate > 0 && $product->expected_courier_cost > 0
                        ? round(($product->return_loss_estimate / $product->expected_courier_cost) * 100, 2)
                        : 0);
                }),
            Forms\Components\TextInput::make('quantity')->integer()->required(),
            Forms\Components\TextInput::make('selling_price')->numeric()->required(),
            Forms\Components\TextInput::make('product_cost')->numeric()->required(),
            Forms\Components\TextInput::make('courier_cost')->numeric()->required(),
            Forms\Components\TextInput::make('expected_return_percentage')->numeric()->required(),
            Forms\Components\TextInput::make('expected_profit')->disabled()->dehydrated(false),
            Forms\Components\Select::make('status')->options([
                'queued' => 'Queued (Confirmed)',
                'dispatched' => 'Dispatched',
                'delivered' => 'Delivered',
                'returned' => 'Returned',
            ])->default('queued')->required(),
            Forms\Components\TextInput::make('delivered_quantity')->integer()->default(0),
            Forms\Components\TextInput::make('returned_quantity')->integer()->default(0),
            Forms\Components\TextInput::make('actual_profit')->disabled()->dehydrated(false),
            Forms\Components\Textarea::make('notes'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('operation_date')->date()->sortable(),
            Tables\Columns\TextColumn::make('businessUnit.name')->label('Business Unit')->sortable(),
            Tables\Columns\TextColumn::make('product.name')->sortable(),
            Tables\Columns\BadgeColumn::make('status'),
            Tables\Columns\TextColumn::make('quantity'),
            Tables\Columns\TextColumn::make('expected_profit')->money('LKR')->sortable(),
            Tables\Columns\TextColumn::make('actual_profit')->money('LKR')->sortable(),
        ])->filters([
            Tables\Filters\Filter::make('date_range')->form([
                Forms\Components\DatePicker::make('from'),
                Forms\Components\DatePicker::make('until'),
            ])->query(function ($query, array $data) {
                return $query
                    ->when($data['from'] ?? null, fn ($q, $date) => $q->whereDate('operation_date', '>=', $date))
                    ->when($data['until'] ?? null, fn ($q, $date) => $q->whereDate('operation_date', '<=', $date));
            }),
            Tables\Filters\SelectFilter::make('business_unit_id')->label('Business Unit')->options(BusinessUnit::query()->pluck('name', 'id')),
            Tables\Filters\SelectFilter::make('product_id')->label('Product')->options(Product::query()->pluck('name', 'id')),
            Tables\Filters\SelectFilter::make('status')->options([
                'queued' => 'Queued',
                'dispatched' => 'Dispatched',
                'delivered' => 'Delivered',
                'returned' => 'Returned',
            ]),
        ])->actions([
            Tables\Actions\EditAction::make(),
        ])->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDailyCODOperations::route('/'),
            'create' => Pages\CreateDailyCODOperation::route('/create'),
            'edit' => Pages\EditDailyCODOperation::route('/{record}/edit'),
        ];
    }
}
