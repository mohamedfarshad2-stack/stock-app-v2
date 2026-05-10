<?php

namespace App\Services;

use App\Models\Order;

class CODProfitService
{

    public function getStats()
    {

        $orders = Order::count();

        $delivered = Order::where('delivery_status','delivered')->count();

        $returned = Order::where('delivery_status','returned')->count();

        $deliveredCourier = $delivered * 350;

        $returnedCourier = $returned * 175;

        return [

            'orders' => $orders,
            'delivered' => $delivered,
            'returned' => $returned,
            'courier_cost' => $deliveredCourier + $returnedCourier,
            'return_loss' => $returnedCourier

        ];

    }

}