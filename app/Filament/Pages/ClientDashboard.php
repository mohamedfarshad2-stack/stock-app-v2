<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ClientDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $navigationGroup = 'COD System';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.client-dashboard';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->role == 2;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->role == 2;
    }
}
