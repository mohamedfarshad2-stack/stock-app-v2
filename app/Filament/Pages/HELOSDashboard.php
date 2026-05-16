<?php

namespace App\Filament\Pages;

use App\Models\DailyCODOperation;
use App\Models\MoneyRecord;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Filament\Pages\Page;

class HELOSDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'HELOS';
    protected static ?string $navigationLabel = 'HELOS Dashboard';
    protected static string $view = 'filament.pages.helos-dashboard';

    public function getViewData(): array
    {
        $today = now()->toDateString();
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

        $expectedToday = (float) DailyCODOperation::query()
            ->whereDate('operation_date', $today)
            ->sum('expected_profit');

        $expectedMonth = (float) DailyCODOperation::query()
            ->whereBetween('operation_date', [$start, $end])
            ->sum('expected_profit');

        $revenueToday = (float) DailyCODOperation::query()
            ->whereDate('operation_date', $today)
            ->sum(DB::raw('quantity * selling_price'));

        $revenueMonth = (float) DailyCODOperation::query()
            ->whereBetween('operation_date', [$start, $end])
            ->sum(DB::raw('quantity * selling_price'));

        $topProducts = DailyCODOperation::query()
            ->select('products.name', DB::raw('SUM(daily_cod_operations.expected_profit) as expected_profit'))
            ->join('products', 'products.id', '=', 'daily_cod_operations.product_id')
            ->whereBetween('daily_cod_operations.operation_date', [$start, $end])
            ->groupBy('products.name')
            ->orderByDesc('expected_profit')
            ->limit(5)
            ->get();

        $highRiskProducts = Product::query()
            ->select(['name', DB::raw("CASE WHEN expected_courier_cost > 0 THEN (return_loss_estimate / expected_courier_cost) * 100 ELSE 0 END as return_risk")])
            ->orderByDesc('return_risk')
            ->limit(5)
            ->get();

        $businessUnitSummary = DailyCODOperation::query()
            ->select('business_units.name', DB::raw('SUM(daily_cod_operations.expected_profit) as expected_profit'))
            ->join('business_units', 'business_units.id', '=', 'daily_cod_operations.business_unit_id')
            ->whereBetween('daily_cod_operations.operation_date', [$start, $end])
            ->groupBy('business_units.name')
            ->orderByDesc('expected_profit')
            ->limit(10)
            ->get();

        return [
            'kpis' => [
                ['label' => 'Revenue', 'value' => 'Rs. ' . number_format($revenue, 2)],
                ['label' => 'Expenses', 'value' => 'Rs. ' . number_format($expenses, 2)],
                ['label' => 'Net Profit', 'value' => 'Rs. ' . number_format($netProfit, 2)],
                ['label' => 'Active Teams', 'value' => (string) $activeTeams],
            ],
            'expectedToday' => $expectedToday,
            'expectedMonth' => $expectedMonth,
            'revenueToday' => $revenueToday,
            'revenueMonth' => $revenueMonth,
            'topProducts' => $topProducts,
            'highRiskProducts' => $highRiskProducts,
            'businessUnitSummary' => $businessUnitSummary,
        ];
    }
}
