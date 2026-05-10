<?php

namespace App\Services;

use App\Models\Address;

class AddressFraudService
{

    public function detect($addressHash)
    {

        $count = Address::where('address_hash',$addressHash)->count();

        if ($count >= 3) {

            return [
                'fraud' => true,
                'reason' => 'Multiple customers using same address'
            ];
        }

        return [
            'fraud' => false
        ];
    }

}