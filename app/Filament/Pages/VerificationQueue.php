<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Notifications\Notification;

use App\Models\Order;

class VerificationQueue extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationGroup = 'Legacy';

    protected static ?string $title = 'Verification Queue';

    protected static string $view = 'filament.pages.verification-queue';

      protected static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 1;
    }


    /**
     * Orders needing verification
     */
    protected function getTableQuery()
    {
        return Order::query()
            ->with(['customer','channel'])
            // ->where('recommended_action','!=','ship_direct')
            // ->where('delivery_status','pending')
            ->where('delivery_status', 'pending')
->where(function ($query) {
    $query->whereNull('verification_status')
          ->orWhere('verification_status', 'pending')
          ->orWhere('verification_status', 'no_answer');
})
            ->orderBy('created_at','desc');
    }


    /**
     * Table Columns
     */
    protected function getTableColumns(): array
    {
        return [

            Tables\Columns\TextColumn::make('id')
                ->label('Order')
                ->sortable(),

            Tables\Columns\TextColumn::make('customer.name')
                ->label('Customer')
                ->searchable(),

            Tables\Columns\TextColumn::make('customer.phone')
                ->label('Phone'),

            Tables\Columns\TextColumn::make('channel.name')
                ->label('Channel'),

            Tables\Columns\TextColumn::make('city')
                ->label('City'),

            Tables\Columns\TextColumn::make('total_amount')
                ->label('Amount')
                ->money('LKR'),

            Tables\Columns\BadgeColumn::make('risk_level')
                ->label('Risk')
                ->colors([
                    'success' => 'low',
                    'primary' => 'medium',
                    'warning' => 'high',
                    'danger' => 'very_high',
                ]),

            Tables\Columns\BadgeColumn::make('recommended_action')
                ->label('Action')
                ->colors([
                    'success' => 'ship_direct',
                    'warning' => 'call_verify',
                    'primary' => 'manual_review',
                    'danger' => 'block_cod',
                ]),

        ];
    }


    /**
     * Call Center Actions
     */
    protected function getTableActions(): array
    {
        return [

            // Tables\Actions\Action::make('confirm')
            //     ->label('Confirm')
            //     ->color('success')
            //     ->action(function ($record) {

            //         $record->update([
            //             'verification_status' => 'confirmed',
            //             'recommended_action' => 'ship_direct'
            //         ]);

            //     }),

Tables\Actions\Action::make('confirm')
    ->label('Confirm')
    ->color('success')
    ->requiresConfirmation()
    ->action(function ($record) {

        try {
            $record->update([
                'verification_status' => 'confirmed',
                'recommended_action' => 'ship_direct'
            ]);

            Notification::make()
                ->title('Order Confirmed ✅')
                ->success()
                ->send();

        } catch (\Throwable $e) {

            Notification::make()
                ->title('Error: '.$e->getMessage())
                ->danger()
                ->send();
        }

    }),

            // Tables\Actions\Action::make('no_answer')
            //     ->label('No Answer')
            //     ->color('warning')
            //     ->action(function ($record) {

            //         $record->update([
            //             'verification_status' => 'no_answer'
            //         ]);

            //         $record->customer->increment('no_answer_count');

            //     }),
            Tables\Actions\Action::make('no_answer')
    ->label('No Answer')
    ->color('warning')
    ->action(function ($record) {

        try {
            $record->update([
                'verification_status' => 'no_answer'
            ]);

            // ✅ SAFE check
            if ($record->customer) {
                $record->customer->increment('no_answer_count');
            }

            \Filament\Notifications\Notification::make()
                ->title('Marked as No Answer ⚠️')
                ->warning()
                ->send();

        } catch (\Throwable $e) {

            \Filament\Notifications\Notification::make()
                ->title('Error: '.$e->getMessage())
                ->danger()
                ->send();
        }

    }),

            // Tables\Actions\Action::make('fake')
            //     ->label('Fake')
            //     ->color('danger')
            //     ->action(function ($record) {

            //         $record->update([
            //             'verification_status' => 'fake'
            //         ]);

            //         $record->customer->increment('fake_order_count');

            //         // block future COD
            //         $record->customer->update([
            //             'is_blacklisted' => true
            //         ]);

            //     }),
            Tables\Actions\Action::make('fake')
    ->label('Fake')
    ->color('danger')
    ->action(function ($record) {

        try {
            $record->update([
                'verification_status' => 'fake'
            ]);

            if ($record->customer) {
                $record->customer->increment('fake_order_count');

                $record->customer->update([
                    'is_blacklisted' => true
                ]);
            }

            \Filament\Notifications\Notification::make()
                ->title('Customer marked as Fake 🚫')
                ->danger()
                ->send();

        } catch (\Throwable $e) {

            \Filament\Notifications\Notification::make()
                ->title('Error: '.$e->getMessage())
                ->danger()
                ->send();
        }

    }),

            Tables\Actions\Action::make('whatsapp')
                ->label('WhatsApp')
                ->color('primary')
                ->url(fn ($record) => 'https://wa.me/'.$record->customer->normalized_phone)
                ->openUrlInNewTab(),

        ];
    }
}