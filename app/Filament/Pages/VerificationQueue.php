<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\RecoveryAttempt;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Tables\Concerns\InteractsWithTable;

class VerificationQueue extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static ?string $navigationGroup = 'Order Management';
    protected static ?string $title = 'Verification Queue';
    protected static string $view = 'filament.pages.verification-queue';

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->role === 1;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 1;
    }

    protected function getTableQuery()
    {
        return Order::query()
            ->with(['customer', 'channel'])
            ->where('delivery_status', 'pending')
            ->where(function ($query) {
                $query->whereNull('verification_status')
                    ->orWhere('verification_status', 'pending')
                    ->orWhere('verification_status', 'no_answer');
            })
            ->orderBy('created_at', 'desc');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')->label('Order')->sortable(),
            Tables\Columns\TextColumn::make('customer.name')->label('Customer')->searchable(),
            Tables\Columns\TextColumn::make('customer.phone')->label('Phone'),
            Tables\Columns\TextColumn::make('channel.name')->label('Channel'),
            Tables\Columns\TextColumn::make('city')->label('City'),
            Tables\Columns\TextColumn::make('total_amount')->label('Amount')->money('LKR'),
            Tables\Columns\BadgeColumn::make('risk_level')->label('Risk')->colors([
                'success' => 'low',
                'primary' => 'medium',
                'warning' => 'high',
                'danger' => 'very_high',
            ]),
            Tables\Columns\BadgeColumn::make('recommended_action')->label('Action')->colors([
                'success' => 'ship_direct',
                'warning' => 'call_verify',
                'primary' => 'manual_review',
                'danger' => 'block_cod',
            ]),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('confirm')
                ->label('Confirm')
                ->color('success')
                ->form([
                    Forms\Components\Textarea::make('outcome_note')
                        ->label('Manual Note')
                        ->placeholder('Optional: customer response or context')
                        ->maxLength(1000),
                ])
                ->requiresConfirmation()
                ->action(function ($record, array $data) {
                    try {
                        $record->update([
                            'verification_status' => 'confirmed',
                            'recommended_action' => 'ship_direct',
                        ]);

                        RecoveryAttempt::create([
                            'order_id' => $record->id,
                            'actor_type' => 'agent',
                            'channel' => 'manual',
                            'outcome_code' => 'confirmed',
                            'outcome_note' => $data['outcome_note'] ?? null,
                            'attempted_at' => now(),
                            'created_by' => auth()->id(),
                        ]);

                        Notification::make()->title('Order Confirmed ✅')->success()->send();
                    } catch (\Throwable $e) {
                        Notification::make()->title('Error: ' . $e->getMessage())->danger()->send();
                    }
                }),

            Tables\Actions\Action::make('no_answer')
                ->label('No Answer')
                ->color('warning')
                ->form([
                    Forms\Components\Textarea::make('outcome_note')
                        ->label('Manual Note')
                        ->placeholder('Optional: call outcome / WhatsApp context')
                        ->maxLength(1000),
                ])
                ->action(function ($record, array $data) {
                    try {
                        $record->update([
                            'verification_status' => 'no_answer',
                        ]);

                        if ($record->customer) {
                            $record->customer->increment('no_answer_count');
                        }

                        RecoveryAttempt::create([
                            'order_id' => $record->id,
                            'actor_type' => 'agent',
                            'channel' => 'manual',
                            'outcome_code' => 'no_answer',
                            'outcome_note' => $data['outcome_note'] ?? null,
                            'attempted_at' => now(),
                            'created_by' => auth()->id(),
                        ]);

                        Notification::make()->title('Marked as No Answer ⚠️')->warning()->send();
                    } catch (\Throwable $e) {
                        Notification::make()->title('Error: ' . $e->getMessage())->danger()->send();
                    }
                }),

            Tables\Actions\Action::make('fake')
                ->label('Fake')
                ->color('danger')
                ->form([
                    Forms\Components\Textarea::make('outcome_note')
                        ->label('Manual Note')
                        ->placeholder('Optional: reason for fake decision')
                        ->maxLength(1000),
                ])
                ->action(function ($record, array $data) {
                    try {
                        $record->update([
                            'verification_status' => 'fake',
                        ]);

                        if ($record->customer) {
                            $record->customer->increment('fake_order_count');
                            $record->customer->update([
                                'is_blacklisted' => true,
                            ]);
                        }

                        RecoveryAttempt::create([
                            'order_id' => $record->id,
                            'actor_type' => 'agent',
                            'channel' => 'manual',
                            'outcome_code' => 'fake',
                            'outcome_note' => $data['outcome_note'] ?? null,
                            'attempted_at' => now(),
                            'created_by' => auth()->id(),
                        ]);

                        Notification::make()->title('Customer marked as Fake 🚫')->danger()->send();
                    } catch (\Throwable $e) {
                        Notification::make()->title('Error: ' . $e->getMessage())->danger()->send();
                    }
                }),

            Tables\Actions\Action::make('whatsapp')
                ->label('WhatsApp')
                ->color('primary')
                ->url(fn($record) => 'https://wa.me/' . $record->customer->normalized_phone)
                ->openUrlInNewTab(),
        ];
    }
}
