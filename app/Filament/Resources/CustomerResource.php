<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\RelationManagers\OrdersRelationManager;
use App\Filament\Resources\CustomerResource\Pages;

use App\Models\Customer;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use App\Filament\Resources\CustomerResource\RelationManagers\EventsRelationManager;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Customer Intelligence';

      protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->role === 1;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 1;
    }


    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    */

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->required(),

                Hidden::make('user_id')
                    ->default(fn () => auth()->id()),

                TextInput::make('alternate_phone'),
                    
                TextInput::make('email')
    ->email()
    ->maxLength(255),

                TextInput::make('city'),

                TextInput::make('trust_score')
                    ->numeric()
                    ->default(0),

                Toggle::make('is_blacklisted')
                    ->label('Blacklisted'),

            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id')
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('phone')
                    ->searchable(),

                TextColumn::make('alternate_phone'),
                // ->label('Alternate Phone')
                // ->tel()
                // ->maxLength(20),

                TextColumn::make('email'),

                TextColumn::make('city'),

                TextColumn::make('total_orders')
                    ->label('Orders'),

                TextColumn::make('returned_orders')
                    ->label('Returns'),

                TextColumn::make('return_rate')
                    ->label('Return %')
                    ->getStateUsing(function ($record) {

                        if ($record->total_orders == 0) {
                            return 0;
                        }

                        return round(($record->returned_orders / $record->total_orders) * 100, 2);
                    }),

                TextColumn::make('trust_score')
                    ->label('Trust Score'),
                    
                TextColumn::make('fake_order_count')
                ->label('Fake Orders'),

    //             TextColumn::make('profit')
    // ->label('Customer Profit')
    // ->getStateUsing(function ($record) {

    //     $delivered = $record->delivered_orders;
    //     $returned = $record->returned_orders;

    //     $revenue = $delivered * 2000;

    //     $courierDelivered = $delivered * 350;
    //     $courierReturned = $returned * 175;

    //     return $revenue - ($courierDelivered + $courierReturned);

    // }),

    TextColumn::make('profit')
    ->label('Customer Profit')
    ->getStateUsing(function ($record) {

        $deliveredOrders = $record->orders()
            ->where('delivery_status', 'delivered');

        $revenue = $deliveredOrders->sum('total_amount');

        $deliveredCount = $deliveredOrders->count();

        $returnedCount = $record->orders()
            ->where('delivery_status', 'cancelled')
            ->count();

        $courierDelivered = $deliveredCount * 350;
        $courierReturned = $returnedCount * 175;

        return $revenue - ($courierDelivered + $courierReturned);
    }),
                TextColumn::make('normalized_phone')
                ->label('Normalized Phone'),


                BadgeColumn::make('risk_level')
                    ->colors([
                        'success' => 'low',
                        'primary' => 'medium',
                        'warning' => 'high',
                        'danger' => 'very_high',
                    ]),

                BadgeColumn::make('is_blacklisted')
                    ->label('Blacklisted')
                    ->colors([
                        'danger' => true,
                        'success' => false,
                    ])
                    ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),

            ])

            ->filters([
                //
            ])

            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])

            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public static function getRelations(): array
    {
        return [

        OrdersRelationManager::class,
        EventsRelationManager::class,

    ];
    }


    /*
    |--------------------------------------------------------------------------
    | PAGES
    |--------------------------------------------------------------------------
    */

    public static function getPages(): array
    {
        return [

            'index' => Pages\ListCustomers::route('/'),

            'create' => Pages\CreateCustomer::route('/create'),

            'view' => Pages\ViewCustomer::route('/{record}'),

            'edit' => Pages\EditCustomer::route('/{record}/edit'),

        ];
    }
}