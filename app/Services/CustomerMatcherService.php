<?php

namespace App\Services;

use App\Models\Customer;
use App\Services\PhoneNormalizerService;

class CustomerMatcherService
{

    protected $phoneNormalizer;

    public function __construct()
    {
        $this->phoneNormalizer = new PhoneNormalizerService();
    }


    public function findOrCreate(array $data)
    {

        $normalizedPhone = $this->phoneNormalizer->normalize($data['phone']);

        // Try to find existing customer
        $customer = Customer::where('normalized_phone', $normalizedPhone)->first();

        if ($customer) {

            // Update last order date
            $customer->update([
                'last_order_at' => now()
            ]);

            return $customer;
        }

        // Create new customer
        $customer = Customer::create([

            'name' => $data['name'] ?? 'Unknown',

            'phone' => $data['phone'],

            'normalized_phone' => $normalizedPhone,

            'address' => $data['address'] ?? null,

            'city' => $data['city'] ?? null,

            'district' => $data['district'] ?? null,

            'first_order_at' => now(),

            'last_order_at' => now()

        ]);

        return $customer;
    }

}