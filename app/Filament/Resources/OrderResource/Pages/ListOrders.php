<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getTitle(): string
    {
        return 'Order Operations Queue';
    }

    protected function getSubheading(): ?string
    {
        return 'Prioritize pending verification, tracking updates, and delivery-state progression.';
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add Manual Order'),
        ];
    }
}
