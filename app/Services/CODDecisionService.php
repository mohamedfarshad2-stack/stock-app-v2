<?php

namespace App\Services;

class CODDecisionService
{

    public function decide($riskScore)
    {

        if ($riskScore < 20) {

            return [
                'risk_level' => 'low',
                'recommended_action' => 'ship_direct'
            ];
        }

        if ($riskScore < 40) {

            return [
                'risk_level' => 'medium',
                'recommended_action' => 'call_verify'
            ];
        }

        if ($riskScore < 70) {

            return [
                'risk_level' => 'high',
                'recommended_action' => 'manual_review'
            ];
        }

        return [
            'risk_level' => 'very_high',
            'recommended_action' => 'block_cod'
        ];

    }

}