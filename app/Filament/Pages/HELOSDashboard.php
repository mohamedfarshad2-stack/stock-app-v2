<?php

namespace App\Filament\Pages;

use App\Models\DailyCODOperation;
use App\Models\MoneyRecord;
use App\Models\Product;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

        $hasDailyOps = Schema::hasTable('daily_cod_operations');
        $hasProducts = Schema::hasTable('products');
        $hasMoneyRecords = Schema::hasTable('money_records');

        $revenue = $hasMoneyRecords
            ? (float) MoneyRecord::where('type', 'income')
                ->whereBetween('record_date', [$start, $end])
                ->sum('amount')
            : 0;

        $expenses = $hasMoneyRecords
            ? (float) MoneyRecord::where('type', 'expense')
                ->whereBetween('record_date', [$start, $end])
                ->sum('amount')
            : 0;

        $netProfit = $revenue - $expenses;

        $activeTeams = $hasMoneyRecords
            ? (int) MoneyRecord::query()
                ->whereBetween('record_date', [$start, $end])
                ->distinct('business_unit_id')
                ->count('business_unit_id')
            : 0;

        $expectedToday = $hasDailyOps
            ? (float) DailyCODOperation::query()
                ->whereDate('operation_date', $today)
                ->sum('expected_profit')
            : 0;

        $expectedMonth = $hasDailyOps
            ? (float) DailyCODOperation::query()
                ->whereBetween('operation_date', [$start, $end])
                ->sum('expected_profit')
            : 0;

        $revenueToday = $hasDailyOps
            ? (float) DailyCODOperation::query()
                ->whereDate('operation_date', $today)
                ->sum(DB::raw('quantity * selling_price'))
            : 0;

        $revenueMonth = $hasDailyOps
            ? (float) DailyCODOperation::query()
                ->whereBetween('operation_date', [$start, $end])
                ->sum(DB::raw('quantity * selling_price'))
            : 0;

        $topProducts = $hasDailyOps && $hasProducts
            ? DailyCODOperation::query()
                ->select(
                    'products.name',
                    DB::raw('SUM(daily_cod_operations.expected_profit) as expected_profit')
                )
                ->join('products', 'products.id', '=', 'daily_cod_operations.product_id')
                ->whereBetween('daily_cod_operations.operation_date', [$start, $end])
                ->groupBy('products.name')
                ->orderByDesc('expected_profit')
                ->limit(5)
                ->get()
            : collect();

        $hasReturnLossEstimate = $hasProducts
            && Schema::hasColumn('products', 'return_loss_estimate');

        $highRiskProducts = $hasProducts
            ? Product::query()
                ->select([
                    'name',
                    DB::raw(
                        $hasReturnLossEstimate
                            ? "CASE WHEN expected_courier_cost > 0 THEN (return_loss_estimate / expected_courier_cost) * 100 ELSE 0 END as return_risk"
                            : '0 as return_risk'
                    ),
                ])
                ->orderByDesc('return_risk')
                ->limit(5)
                ->get()
            : collect();

        $businessUnitSummary = $hasDailyOps
            && Schema::hasTable('business_units')
            ? DailyCODOperation::query()
                ->select(
                    'business_units.name',
                    DB::raw('SUM(daily_cod_operations.expected_profit) as expected_profit')
                )
                ->join(
                    'business_units',
                    'business_units.id',
                    '=',
                    'daily_cod_operations.business_unit_id'
                )
                ->whereBetween('daily_cod_operations.operation_date', [$start, $end])
                ->groupBy('business_units.name')
                ->orderByDesc('expected_profit')
                ->limit(10)
                ->get()
            : collect();

        return [
            'kpis' => [
                [
                    'label' => 'Revenue',
                    'value' => 'Rs. ' . number_format($revenue, 2),
                ],
                [
                    'label' => 'Expenses',
                    'value' => 'Rs. ' . number_format($expenses, 2),
                ],
                [
                    'label' => 'Net Profit',
                    'value' => 'Rs. ' . number_format($netProfit, 2),
                ],
                [
                    'label' => 'Active Teams',
                    'value' => (string) $activeTeams,
                ],
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