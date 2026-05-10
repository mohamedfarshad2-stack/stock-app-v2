<?php

namespace App\Services;

use App\Models\Customer;
use App\Services\CODDecisionService;

class RiskScoringService
{

    public function evaluate(Customer $customer)
    {

        $score = 0;

        $totalOrders = $customer->total_orders;
        $delivered = $customer->delivered_orders;
        $returned = $customer->returned_orders;

        // If no history → medium risk
        if ($totalOrders == 0) {

            return [
                'score' => 0,
                'risk_level' => 'medium',
                'recommended_action' => 'call_verify'
            ];
        }

        // Delivery success rate
        $successRate = ($delivered / $totalOrders) * 100;

        // High success customers
        if ($successRate >= 80) {
            $score += 20;
        }

        if ($successRate >= 60) {
            $score += 10;
        }

        // Return penalty
        if ($returned >= 1) {
            $score -= 5;
        }

        if ($returned >= 2) {
            $score -= 10;
        }

        if ($returned >= 3) {
            $score -= 20;
        }

        // Fake order penalty
        if ($customer->fake_order_count > 0) {
            $score -= 40;
        }

        // No answer penalty
        if ($customer->no_answer_count >= 3) {
            $score -= 10;
        }

        // Determine risk level
        $riskLevel = 'medium';
        $action = 'call_verify';

        if ($score >= 20) {

            $riskLevel = 'low';
            $action = 'ship_direct';

        } elseif ($score >= 5) {

            $riskLevel = 'medium';
            $action = 'call_verify';

        } elseif ($score >= -10) {

            $riskLevel = 'high';
            $action = 'manual_review';

        } else {

            $riskLevel = 'very_high';
            $action = 'block_cod';

        }

        $decision = (new CODDecisionService())->decide($score);

return [
    'score' => $score,
    'risk_level' => $decision['risk_level'],
    'recommended_action' => $decision['recommended_action']
];

    }

}