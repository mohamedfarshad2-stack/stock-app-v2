<?php

namespace App\Services;

use App\Models\Order;
use App\Models\CustomerEvent;

use App\Services\CustomerStatsService;
use App\Services\RiskScoringService;

class OrderStatusService
{

    protected $statsService;
    protected $riskService;

    public function __construct()
    {
        $this->statsService = new CustomerStatsService();
        $this->riskService = new RiskScoringService();
    }

    public function updateStatus(Order $order, $status)
    {

        /*
        ---------------------------------
        1️⃣ Update Order Status
        ---------------------------------
        */

        $order->update([
            'delivery_status' => $status
        ]);


        /*
        ---------------------------------
        2️⃣ Log Customer Event
        ---------------------------------
        */

        CustomerEvent::create([

            'customer_id' => $order->customer_id,
            'order_id' => $order->id,

            'event_type' => $status,

            'score_impact' => 0,

            'notes' => "Order status updated to {$status}"

        ]);


        /*
        ---------------------------------
        3️⃣ Refresh Customer Stats
        ---------------------------------
        */

        $customer = $order->customer;

        $this->statsService->refresh($customer);


        /*
        ---------------------------------
        4️⃣ Recalculate Risk Score
        ---------------------------------
        */

        $risk = $this->riskService->evaluate($customer);

        $customer->update([

            'trust_score' => $risk['score'],
            'risk_level' => $risk['risk_level']

        ]);


        return $order;

    }

}