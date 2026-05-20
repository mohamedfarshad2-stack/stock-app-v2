<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;

use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\SelectColumn;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'HELOS Core';
    protected static bool $shouldRegisterNavigation = true;

    public static function canAccess(): bool
    {
        return true;
    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([

            Select::make('customer_id')
                ->label('Customer')
                ->relationship('customer','name')
                ->searchable(['name','phone'])
                ->required()
                ->reactive()
                ->getOptionLabelFromRecordUsing(fn ($record) => $record->name.' - '.$record->phone),

            Placeholder::make('customer_history')
    ->label('Customer History')
    ->content(function ($get) {

        $customerId = $get('customer_id');

        if (!$customerId) {
            return 'No customer selected';
        }

        $orders = \App\Models\Order::where('customer_id', $customerId)->count();

        $returns = \App\Models\Order::where('customer_id', $customerId)
            ->where('delivery_status', 'cancelled')
            ->count();

        // risk logic
        if ($returns >= 5) {
            $risk = 'very_high';
        } elseif ($returns >= 3) {
            $risk = 'high';
        } elseif ($returns >= 1) {
            $risk = 'medium';
        } else {
            $risk = 'low';
        }

        return "Orders: $orders | Returns: $returns | Risk: $risk";
    })
    ->reactive(),

            Select::make('channel_id')
                ->relationship('channel','name')
                ->required(),

            TextInput::make('external_order_no')
                ->label('Order No')
                ->maxLength(255),

            TextInput::make('channel_reference')
                ->label('Tracking / Reference')
                ->reactive()
                ->maxLength(255),

            DatePicker::make('order_date')
                ->default(now())
                ->required(),

            TextInput::make('city')
                ->required(),

            Textarea::make('address'),

            /*
            |--------------------------------------------------------------------------
            | ITEMS
            |--------------------------------------------------------------------------
            */

            Repeater::make('items')
                ->relationship()
                ->schema([

                    TextInput::make('product_name')
                        ->required(),

                    Select::make('product_category_id')
                        ->relationship('category','name')
                        ->label('Category'),

                    TextInput::make('sku'),

                    TextInput::make('quantity')
                        ->numeric()
                        ->default(1)
                        ->reactive()
                        ->required()
                        ->afterStateUpdated(function ($state, callable $get, callable $set) {

                            $price = $get('selling_price') ?? 0;
                            $total = $state * $price;

                            $set('total_price', $total);

                            self::recalculateOrderTotal($get, $set);
                        }),

                    TextInput::make('selling_price')
                        ->numeric()
                        ->reactive()
                        ->required()
                        ->dehydrated()
                        ->afterStateUpdated(function ($state, callable $get, callable $set) {

                            $qty = $get('quantity') ?? 0;
                            $total = $qty * $state;

                            $set('total_price', $total);

                            self::recalculateOrderTotal($get, $set);
                        }),

                    TextInput::make('total_price')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(),

                ])
                ->columns(3)
                ->createItemButtonLabel('Add Item'),

            /*
            |--------------------------------------------------------------------------
            | TOTAL
            |--------------------------------------------------------------------------
            */

            TextInput::make('total_amount')
                ->label('Total Amount')
                ->numeric()
                ->prefix('Rs')
                ->disabled()
                ->dehydrated()
                ->default(0),

            Select::make('verification_status')
                ->options([
                    'pending' => 'Pending',
                    'confirmed' => 'Confirmed',
                    'no_answer' => 'No Answer',
                    'call_back' => 'Call Back',
                ])
                ->default('pending'),

            Select::make('delivery_status')
                ->options([
                    'pending' => 'Pending',
                    'dispatched' => 'Dispatched',
                    'delivered' => 'Delivered',
                    'returned' => 'Returned',
                    'cancelled' => 'Cancelled',
                ])
                ->default('pending'),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    public static function table(Table $table): Table
    {
        return $table->columns([

            TextColumn::make('id')
                ->sortable(),

            TextColumn::make('customer.name')
                ->label('Customer')
                ->formatStateUsing(fn ($record) =>
                    $record->customer
                        ? $record->customer->name.' - '.$record->customer->phone
                        : '-'
                )
                ->searchable(),

            TextColumn::make('channel.name')
                ->label('Channel'),

            TextColumn::make('order_date')
                ->date(),

            TextColumn::make('external_order_no')
                ->label('Order No')
                ->searchable(),

            TextInputColumn::make('channel_reference')
                ->label('Tracking / Ref')
                ->rules(['nullable', 'string', 'max:255'])
                ->afterStateUpdated(function (Order $record, ?string $state): void {
                    if (
                        trim((string) $state) !== ''
                        && $record->delivery_status === 'pending'
                    ) {
                        $record->delivery_status = 'dispatched';
                        $record->save();
                    }
                }),

            TextColumn::make('total_amount')
                ->label('Amount'),

            BadgeColumn::make('verification_status')
                ->label('Confirmation'),

            SelectColumn::make('delivery_status')
                ->label('Delivery')
                ->options([
                    'pending' => 'Pending',
                    'dispatched' => 'Dispatched',
                    'delivered' => 'Delivered',
                    'returned' => 'Returned',
                    'cancelled' => 'Cancelled',
                ]),

            BadgeColumn::make('risk_level')
                ->colors([
                    'success' => 'low',
                    'primary' => 'medium',
                    'warning' => 'high',
                    'danger' => 'very_high'
                ]),

            TextColumn::make('created_at')
                ->dateTime(),

        ])->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\Action::make('markDelivered')
                ->label('Delivered')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(function ($record) {
                    $record->delivery_status = 'delivered';
                    $record->save();
                })
                ->visible(fn ($record) => $record->delivery_status !== 'delivered'),
            Tables\Actions\Action::make('cancelOrder')
                ->label('Cancel')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function ($record) {
                    $record->delivery_status = 'cancelled';
                    $record->save();
                })
                ->visible(fn ($record) => $record->delivery_status !== 'cancelled'),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Helper: Recalculate Order Total
    |--------------------------------------------------------------------------
    */

    protected static function recalculateOrderTotal($get, $set)
    {
        $items = $get('../../items') ?? [];

        $sum = 0;

        foreach ($items as $item) {
            $sum += $item['total_price'] ?? 0;
        }

        $set('../../total_amount', $sum);
    }
}
