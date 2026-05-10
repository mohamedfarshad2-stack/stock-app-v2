<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Customer;

class CustomerStatsService
{

    public function refresh(Customer $customer)
    {

        $totalOrders = $customer->orders()->count();

        $deliveredOrders = $customer->orders()
            ->where('delivery_status', 'delivered')
            ->count();

        $returnedOrders = $customer->orders()
            ->where('delivery_status', 'returned')
            ->count();

        $cancelledOrders = $customer->orders()
            ->where('delivery_status', 'cancelled')
            ->count();

        $lifetimeValue = $customer->orders()
            ->where('delivery_status', 'delivered')
            ->sum('total_amount');

        $customer->update([

            'total_orders' => $totalOrders,
            'delivered_orders' => $deliveredOrders,
            'returned_orders' => $returnedOrders,
            'cancelled_orders' => $cancelledOrders,
            'lifetime_value' => $lifetimeValue

        ]);

        return $customer;
    }

}