<?php

namespace App\Services;

use App\Models\Customer;

class PhoneIntelligenceService
{

    public function analyze($phone)
    {

        $customers = Customer::where('normalized_phone', $phone)->get();

        $names = $customers->pluck('name')->unique();

        $cities = $customers->pluck('city')->unique();

        return [

            'customer_count' => $customers->count(),

            'unique_names' => $names->count(),

            'unique_cities' => $cities->count(),

            'is_suspicious' => $names->count() > 1

        ];

    }

}