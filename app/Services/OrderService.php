<?php

namespace App\Services;

use DB;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CustomerEvent;
use App\Models\Customer;

use App\Services\CustomerMatcherService;
use App\Services\RiskScoringService;
use App\Services\CODBlockingService;
use App\Services\FraudDetectionService;
use App\Services\PhoneIntelligenceService;

class OrderService
{

    protected $customerMatcher;
    protected $riskService;
    protected $blockingService;
    protected $fraudService;
    protected $phoneIntel;

    public function __construct()
    {
        $this->customerMatcher = new CustomerMatcherService();
        $this->riskService = new RiskScoringService();
        $this->blockingService = new CODBlockingService();
        $this->fraudService = new FraudDetectionService();
        $this->phoneIntel = new PhoneIntelligenceService();
    }


    public function createOrder(array $data)
    {

        return DB::transaction(function () use ($data) {

            /*
            ----------------------------
            1️⃣ Find or create customer
            ----------------------------
            */

            $customer = $this->customerMatcher->findOrCreate($data);


            /*
            ----------------------------
            2️⃣ Phone intelligence check
            ----------------------------
            */

            $phoneAnalysis = $this->phoneIntel->analyze($customer->normalized_phone);


            /*
            ----------------------------
            3️⃣ Evaluate customer risk
            ----------------------------
            */

            $risk = $this->riskService->evaluate($customer);


            /*
            ----------------------------
            4️⃣ Fraud detection
            ----------------------------
            */

            $fraudFlags = $this->fraudService->checkCustomer($customer);


            /*
            ----------------------------
            5️⃣ COD blocking check
            ----------------------------
            */

            $blocking = $this->blockingService->check($customer);


            /*
            ----------------------------
            6️⃣ Determine final action
            ----------------------------
            */

            $recommendedAction = $blocking['action'] ?? $risk['recommended_action'];


            /*
            Fraud flags
            */

            if (in_array('too_many_returns', $fraudFlags)) {
                $recommendedAction = 'manual_review';
            }

            if (in_array('high_return_rate', $fraudFlags)) {
                $recommendedAction = 'manual_review';
            }

            if (in_array('fake_customer', $fraudFlags)) {
                $recommendedAction = 'block_cod';
            }


            /*
            Phone intelligence
            */

            if ($phoneAnalysis['is_suspicious']) {

                $recommendedAction = 'manual_review';

                CustomerEvent::create([
                    'customer_id' => $customer->id,
                    'event_type' => 'suspicious_phone',
                    'notes' => 'Phone used by multiple customer names'
                ]);
            }


            /*
            ----------------------------
            7️⃣ Create Order
            ----------------------------
            */

            $order = Order::create([

                'customer_id' => $customer->id,
                'channel_id' => $data['channel_id'],

                'order_date' => now(),

                'subtotal' => $data['subtotal'] ?? 0,
                'discount' => $data['discount'] ?? 0,
                'shipping_fee' => $data['shipping_fee'] ?? 0,
                'total_amount' => $data['total_amount'] ?? 0,

                'payment_method' => $data['payment_method'] ?? 'cod',

                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'district' => $data['district'] ?? null,

                'risk_score' => $risk['score'],
                'risk_level' => $risk['risk_level'],
                'recommended_action' => $recommendedAction,

                'delivery_status' => 'pending'

            ]);


            /*
            ----------------------------
            8️⃣ Create order items
            ----------------------------
            */

            if (!empty($data['items'])) {

                foreach ($data['items'] as $item) {

                    OrderItem::create([

                        'order_id' => $order->id,

                        'product_category_id' => $item['product_category_id'] ?? null,

                        'product_name' => $item['product_name'],

                        'sku' => $item['sku'] ?? null,

                        'quantity' => $item['quantity'] ?? 1,

                        'unit_price' => $item['unit_price'] ?? 0,

                        'total_price' => $item['total_price'] ?? 0

                    ]);

                }

            }


            /*
            ----------------------------
            9️⃣ Log order event
            ----------------------------
            */

            CustomerEvent::create([

                'customer_id' => $customer->id,
                'order_id' => $order->id,

                'event_type' => 'order_created',
                'score_impact' => 0,

                'notes' => 'Order created'

            ]);


            /*
            ----------------------------
            🔟 Update customer stats
            ----------------------------
            */

            $customer->increment('total_orders');

            $customer->update([
                'last_order_at' => now()
            ]);


            return $order;

        });

    }

}