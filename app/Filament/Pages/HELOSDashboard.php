<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class HELOSDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'HELOS';

    protected static ?string $navigationLabel = 'HELOS Dashboard';

    protected static string $view = 'filament.pages.helos-dashboard';

    public function getViewData(): array
    {
        return [
            'kpis' => [
                ['label' => 'Revenue', 'value' => 'Rs. 0.00'],
                ['label' => 'Expenses', 'value' => 'Rs. 0.00'],
                ['label' => 'Net Profit', 'value' => 'Rs. 0.00'],
                ['label' => 'Active Teams', 'value' => '0'],
            ],
        ];
    }
}
