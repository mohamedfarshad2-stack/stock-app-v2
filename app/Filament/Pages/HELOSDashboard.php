<?php

namespace App\Filament\Pages;

use App\Models\MoneyRecord;
use Filament\Pages\Page;

class HELOSDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'HELOS';
    protected static ?string $navigationLabel = 'HELOS Dashboard';
    protected static string $view = 'filament.pages.helos-dashboard';

    public function getViewData(): array
    {
        $start = now()->startOfMonth()->toDateString();
        $end = now()->endOfMonth()->toDateString();

        $revenue = MoneyRecord::where('type', 'income')
            ->whereBetween('record_date', [$start, $end])
            ->sum('amount');

        $expenses = MoneyRecord::where('type', 'expense')
            ->whereBetween('record_date', [$start, $end])
            ->sum('amount');

        $netProfit = $revenue - $expenses;

        $activeTeams = MoneyRecord::query()
            ->whereBetween('record_date', [$start, $end])
            ->distinct('business_unit_id')
            ->count('business_unit_id');

        return [
            'kpis' => [
                ['label' => 'Revenue', 'value' => 'Rs. ' . number_format($revenue, 2)],
                ['label' => 'Expenses', 'value' => 'Rs. ' . number_format($expenses, 2)],
                ['label' => 'Net Profit', 'value' => 'Rs. ' . number_format($netProfit, 2)],
                ['label' => 'Active Teams', 'value' => (string) $activeTeams],
            ],
        ];
    }
}
