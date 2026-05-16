<?php

namespace App\Filament\Pages;

use App\Models\MoneyRecord;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class HELOSDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'HELOS';
    protected static ?string $navigationLabel = 'HELOS Dashboard';
    protected static string $view = 'filament.pages.helos-dashboard';

    public function getViewData(): array
    {
        $today = now()->toDateString();
        $startMonth = now()->startOfMonth()->toDateString();
        $endMonth = now()->endOfMonth()->toDateString();

        $todayIncome = MoneyRecord::where('type', 'income')->whereDate('record_date', $today)->sum('amount');
        $todayExpenses = MoneyRecord::where('type', 'expense')->whereDate('record_date', $today)->sum('amount');
        $monthIncome = MoneyRecord::where('type', 'income')->whereBetween('record_date', [$startMonth, $endMonth])->sum('amount');
        $monthExpenses = MoneyRecord::where('type', 'expense')->whereBetween('record_date', [$startMonth, $endMonth])->sum('amount');
        $receivables = MoneyRecord::where('type', 'receivable')->sum('amount');
        $payables = MoneyRecord::where('type', 'payable')->sum('amount');

        $expensesByUnit = MoneyRecord::query()
            ->select('business_units.name as unit', DB::raw('SUM(money_records.amount) as total'))
            ->join('business_units', 'business_units.id', '=', 'money_records.business_unit_id')
            ->where('money_records.type', 'expense')
            ->whereBetween('money_records.record_date', [$startMonth, $endMonth])
            ->groupBy('business_units.name')
            ->orderByDesc('total')
            ->get();

        $expensesByCategory = MoneyRecord::query()
            ->select('finance_categories.name as category', DB::raw('SUM(money_records.amount) as total'))
            ->join('finance_categories', 'finance_categories.id', '=', 'money_records.finance_category_id')
            ->where('money_records.type', 'expense')
            ->whereBetween('money_records.record_date', [$startMonth, $endMonth])
            ->groupBy('finance_categories.name')
            ->orderByDesc('total')
            ->get();

        $incomeByUnit = MoneyRecord::query()
            ->select('business_units.name as unit', DB::raw('SUM(money_records.amount) as total'))
            ->join('business_units', 'business_units.id', '=', 'money_records.business_unit_id')
            ->where('money_records.type', 'income')
            ->whereBetween('money_records.record_date', [$startMonth, $endMonth])
            ->groupBy('business_units.name')
            ->orderByDesc('total')
            ->get();

        return compact('todayIncome', 'todayExpenses', 'monthIncome', 'monthExpenses', 'receivables', 'payables', 'expensesByUnit', 'expensesByCategory', 'incomeByUnit');
    }
}
