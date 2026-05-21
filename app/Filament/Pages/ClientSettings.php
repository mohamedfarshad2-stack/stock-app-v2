<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ClientSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'Settings';
    protected static ?string $navigationGroup = 'Legacy';
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.client-settings';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->role == 2;
    }
}
