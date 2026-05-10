<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

use App\Models\Order;
use App\Models\Customer;

class CODDashboardStats extends StatsOverviewWidget
{

    protected function getCards(): array
    {

        $todayOrders = Order::whereDate('order_date', today())->count();

        $returnedOrders = Order::where('delivery_status', 'returned')->count();

        $totalOrders = Order::count();

        $returnRate = $totalOrders > 0
            ? round(($returnedOrders / $totalOrders) * 100, 2)
            : 0;

        $blockedCustomers = Customer::where('is_blacklisted', true)->count();

        return [

            Card::make('Orders Today', $todayOrders)
                ->description('Total orders placed today'),

            Card::make('Return Rate %', $returnRate)
                ->description('Overall COD return rate'),

            Card::make('Returned Orders', $returnedOrders)
                ->description('Orders returned'),

            Card::make('Blocked Customers', $blockedCustomers)
                ->description('COD blocked customers'),

        ];
    }

}