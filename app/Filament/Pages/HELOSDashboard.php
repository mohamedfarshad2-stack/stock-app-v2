<?php

namespace App\Filament\Pages;

codex/create-helos-finance-module-foundation-98xta3
use App\Models\MoneyRecord;
use Filament\Pages\Page;
use App\Models\DailyCODOperation;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
 main

class HELOSDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
 codex/create-helos-finance-module-foundation-98xta3

    protected static ?string $navigationGroup = 'HELOS';

    protected static ?string $navigationLabel = 'HELOS Dashboard';


    protected static ?string $navigationGroup = 'HELOS';
    protected static ?string $navigationLabel = 'HELOS Dashboard';
 main
    protected static string $view = 'filament.pages.helos-dashboard';

    public function getViewData(): array
    {
 codex/create-helos-finance-module-foundation-98xta3
        $start = now()->startOfMonth()->toDateString();
        $end = now()->endOfMonth()->toDateString();

        $revenue = MoneyRecord::where('type', 'income')->whereBetween('record_date', [$start, $end])->sum('amount');
        $expenses = MoneyRecord::where('type', 'expense')->whereBetween('record_date', [$start, $end])->sum('amount');
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

        $today = now()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();

        $expectedToday = DailyCODOperation::whereDate('operation_date', $today)->sum('expected_profit');
        $expectedMonth = DailyCODOperation::whereBetween('operation_date', [$monthStart, $today])->sum('expected_profit');
        $revenueToday = DailyCODOperation::whereDate('operation_date', $today)->selectRaw('SUM(quantity * selling_price) as total')->value('total') ?? 0;
        $revenueMonth = DailyCODOperation::whereBetween('operation_date', [$monthStart, $today])->selectRaw('SUM(quantity * selling_price) as total')->value('total') ?? 0;

        $topProducts = DailyCODOperation::query()
            ->join('products', 'daily_cod_operations.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(daily_cod_operations.expected_profit) as expected_profit'))
            ->groupBy('products.name')
            ->orderByDesc('expected_profit')
            ->limit(5)
            ->get();

        $highRiskProducts = DailyCODOperation::query()
            ->join('products', 'daily_cod_operations.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('AVG(daily_cod_operations.expected_return_percentage) as return_risk'))
            ->groupBy('products.name')
            ->orderByDesc('return_risk')
            ->limit(5)
            ->get();

        $businessUnitSummary = DailyCODOperation::query()
            ->join('business_units', 'daily_cod_operations.business_unit_id', '=', 'business_units.id')
            ->select('business_units.name', DB::raw('SUM(daily_cod_operations.expected_profit) as expected_profit'))
            ->groupBy('business_units.name')
            ->orderByDesc('expected_profit')
            ->get();

        return compact('expectedToday', 'expectedMonth', 'revenueToday', 'revenueMonth', 'topProducts', 'highRiskProducts', 'businessUnitSummary');
 main
    }
}
