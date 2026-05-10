<?php

// namespace App\Filament\Pages;

// use Filament\Pages\Page;

// class Dashboard extends Page
// {
//     protected static ?string $navigationIcon = 'heroicon-o-document-text';

//     protected static string $view = 'filament.pages.dashboard';
// }

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function mount(): void
    {
        if (auth()->user()?->role === 2) {
            redirect()->to('/admin/client-dashboard');
            return;
        }

        if (auth()->user()?->role !== 1) {
            redirect()->to('/admin/customer-intelligence');
            return;
        }
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->role === 1;
    }
}
