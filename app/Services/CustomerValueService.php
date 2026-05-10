<?php

namespace App\Services;

use App\Models\Customer;

class CustomerValueService
{

    public function calculate(Customer $customer)
    {

        $delivered = $customer->delivered_orders;
        $returned = $customer->returned_orders;

        $averageOrderValue = 2000; // you can calculate later dynamically

        $revenue = $delivered * $averageOrderValue;

        $courierDelivered = $delivered * 350;

        $courierReturned = $returned * 175;

        $profit = $revenue - ($courierDelivered + $courierReturned);

        return [

            'revenue' => $revenue,
            'profit' => $profit

        ];

    }

}