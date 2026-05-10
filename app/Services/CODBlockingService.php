<?php

namespace App\Services;

use App\Models\Customer;

class CODBlockingService
{

    public function check(Customer $customer)
    {

        // Rule 1 - Fake customer
        if ($customer->fake_order_count >= 1) {

            return [
                'blocked' => true,
                'reason' => 'Fake customer detected'
            ];
        }

        // Rule 2 - Too many returns
        if ($customer->returned_orders >= 3) {

            return [
                'blocked' => true,
                'reason' => 'Too many returned orders'
            ];
        }

        // Rule 3 - High no answer count
        if ($customer->no_answer_count >= 3) {

            return [
                'blocked' => true,
                'reason' => 'Customer unreachable multiple times'
            ];
        }

        return [
            'blocked' => false
        ];

    }

}