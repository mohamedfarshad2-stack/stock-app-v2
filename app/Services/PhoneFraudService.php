<?php

namespace App\Services;

use App\Models\Customer;

class PhoneFraudService
{

    public function detect($normalizedPhone)
    {

        $customers = Customer::where('normalized_phone', $normalizedPhone)->get();

        if ($customers->count() > 1) {

            return [
                'fraud' => true,
                'reason' => 'Multiple customer profiles using same phone'
            ];
        }

        return [
            'fraud' => false
        ];
    }

}