<?php

namespace App\Services;

use App\Models\Customer;

class FraudDetectionService
{

    public function checkCustomer(Customer $customer)
    {

        $flags = [];

        // too many returns
        if ($customer->returned_orders >= 3) {
            $flags[] = 'too_many_returns';
        }

        // too many no answers
        if ($customer->no_answer_count >= 3) {
            $flags[] = 'unreachable_customer';
        }

        // fake orders detected
        if ($customer->fake_order_count >= 1) {
            $flags[] = 'fake_customer';
        }

        // calculate return rate
        if ($customer->total_orders > 0) {

            $returnRate = ($customer->returned_orders / $customer->total_orders) * 100;

            if ($returnRate >= 50) {
                $flags[] = 'high_return_rate';
            }

        }

        return $flags;

    }

}