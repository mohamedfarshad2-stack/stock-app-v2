<?php

namespace App\Services;

use App\Models\Customer;

class ReputationService
{

    public function getBadge(Customer $customer)
    {

        $score = $customer->trust_score;

        if ($score >= 80) {
            return 'gold';
        }

        if ($score >= 60) {
            return 'trusted';
        }

        if ($score >= 40) {
            return 'normal';
        }

        if ($score >= 20) {
            return 'risky';
        }

        return 'blocked';

    }

}