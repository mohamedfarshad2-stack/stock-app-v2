<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;

class CustomerIntelligenceService
{
    private function maskValue($value, $visiblePercent = 40)
{
    if (!$value) return '-';

    $length = strlen($value);
    $visibleLength = ceil($length * ($visiblePercent / 100));

    return substr($value, 0, $visibleLength) . str_repeat('X', $length - $visibleLength);
}

    public function build(string $search): ?array
{
    

    $normalized = Customer::normalizePhone($search);

    $customers = Customer::query()
        ->where('normalized_phone', $normalized)
        ->orWhere('normalized_alternate_phone', $normalized)
        ->orWhere('normalized_whatsapp_number', $normalized)
        ->orWhere('phone', $search)
        ->orWhere('alternate_phone', $search)
        ->orWhere('whatsapp_number', $search)
        ->get();

    if ($customers->isEmpty()) {
        return null;
    }


    // ✅ Always use primary customer cleanly
    $primaryCustomer = $customers->sortByDesc('id')->first();

    $user = auth()->user();
$isPremium = $user->client_type === 'premium';

// 🔒 Mask customer data for non-premium
if (!$isPremium && $primaryCustomer) {
    $primaryCustomer->phone = $this->maskValue($primaryCustomer->phone);
    $primaryCustomer->alternate_phone = $this->maskValue($primaryCustomer->alternate_phone);
    $primaryCustomer->city = $this->maskValue($primaryCustomer->city);
}


    $customerIds = $customers->pluck('id')->unique()->values();

    $orders = Order::query()
        ->whereIn('customer_id', $customerIds)
        ->latest()
        ->get();

        // 🔒 Mask order data
$orders = $orders->map(function ($order) use ($isPremium) {

    if (!$isPremium) {
        $order->address = $this->maskValue($order->address);
        $order->city = $this->maskValue($order->city);
    }

    return $order;
});

    // ✅ SAFE: even if no orders
    $summary = $this->buildSummary($customers, $orders ?? collect());
    $financial = $this->buildFinancialSummary($orders ?? collect(), $summary);
    if (!$isPremium) {
    // $financial['ltv'] = 'upgrade';
    $financial['ltv'] = [
    'locked' => true,
    'message' => 'Upgrade to view'
];
}
    $risk = $this->buildRiskSummary($summary, $financial);
    $prediction = $this->buildPrediction($summary, $risk);
    $segment = $this->buildSegment($summary, $financial, $risk);
    $recommendation = $this->buildRecommendation($prediction, $summary);

    return [
        'customers' => $customers,              // collection
        'primary_customer' => $primaryCustomer, // ✅ ALWAYS MODEL
        'orders' => $orders ?? collect(),       // safe fallback

        'summary' => $summary ?? [],
        'financial' => $financial ?? [],
        'risk' => $risk ?? [],
        'prediction' => $prediction ?? [],
        'segment' => $segment ?? null,
        'recommendation' => $recommendation ?? [],
    ];
}

    protected function buildSummary($customers, $orders): array
    {
        $totalOrders = $orders->count();

        $delivered = $orders->filter(fn ($o) => $o->delivery_status === 'delivered')->count();
        $cancelled = $orders->filter(fn ($o) => $o->delivery_status === 'cancelled')->count();

        $pending = $orders->filter(function ($o) {
            return !in_array($o->delivery_status, ['delivered', 'cancelled'], true);
        })->count();

        $fakeOrders = $orders->filter(fn ($o) => $o->verification_status === 'fake')->count();
        $noAnswerCount = $orders->filter(fn ($o) => $o->verification_status === 'no_answer')->count();

        // "Real returns" = cancelled orders that were not fake / no answer
        $realReturns = max(0, $cancelled - ($fakeOrders + $noAnswerCount));

        $successRate = $totalOrders > 0 ? round(($delivered / $totalOrders) * 100, 2) : 0;

        // keep both:
        // return_rate = total failed delivery rate
        // real_return_rate = actual customer rejection-ish rate
        $returnRate = $totalOrders > 0 ? round(($cancelled / $totalOrders) * 100, 2) : 0;
        $realReturnRate = $totalOrders > 0 ? round(($realReturns / $totalOrders) * 100, 2) : 0;

        $lastOrder = $orders->first();
        $firstOrder = $orders->last();

        $recent30 = $orders->filter(fn ($o) => optional($o->created_at)?->gte(now()->subDays(30)))->count();
        $recent90 = $orders->filter(fn ($o) => optional($o->created_at)?->gte(now()->subDays(90)))->count();

        $lastFive = $orders->take(5)->values();

        $consecutiveReturns = 0;
        foreach ($lastFive as $order) {
            if ($order->delivery_status === 'cancelled') {
                $consecutiveReturns++;
            } else {
                break;
            }
        }

        $consecutiveDeliveries = 0;
        foreach ($lastFive as $order) {
            if ($order->delivery_status === 'delivered') {
                $consecutiveDeliveries++;
            } else {
                break;
            }
        }

        $lastDelivered = $orders->first(fn ($o) => $o->delivery_status === 'delivered');

        $usedNamesCount = $customers->pluck('name')
            ->filter()
            ->map(fn ($name) => trim(mb_strtolower($name)))
            ->unique()
            ->count();

        return [
            'total_orders' => $totalOrders,
            'delivered_orders' => $delivered,
            'cancelled_orders' => $cancelled,
            'returned_orders' => $realReturns,
            'pending_orders' => $pending,

            'fake_orders' => $fakeOrders,
            'no_answer_count' => $noAnswerCount,

            'success_rate' => $successRate,
            'return_rate' => $returnRate,
            'real_return_rate' => $realReturnRate,

            'first_order_date' => optional($firstOrder?->created_at)?->toDateString(),
            'last_order_date' => optional($lastOrder?->created_at)?->toDateString(),
            'recent_30_day_orders' => $recent30,
            'recent_90_day_orders' => $recent90,

            'consecutive_returns' => $consecutiveReturns,
            'consecutive_deliveries' => $consecutiveDeliveries,

            'last_order_status' => $lastOrder?->delivery_status,
            'days_since_last_successful_delivery' => $lastDelivered?->created_at
                ? $lastDelivered->created_at->diffInDays(now())
                : null,

            'used_names_count' => $usedNamesCount,
            'possible_duplicate_profiles' => $customers->count() > 1,
        ];
    }

    protected function buildFinancialSummary($orders, array $summary): array
    {
        $deliveredOrders = $orders->filter(fn ($o) => $o->delivery_status === 'delivered');
        $cancelledOrders = $orders->filter(fn ($o) => $o->delivery_status === 'cancelled');

        $grossRevenue = (float) $deliveredOrders->sum('total_amount');
        $cancelledValue = (float) $cancelledOrders->sum('total_amount');
        $returnedValue = (float) $cancelledOrders->sum('total_amount');

        $avgOrderValue = $deliveredOrders->count() > 0
            ? round($grossRevenue / $deliveredOrders->count(), 2)
            : 0;

        $courierDelivered = $deliveredOrders->count() * 350;
        $courierCancelled = $cancelledOrders->count() * 175;
        $courierLoss = $courierDelivered + $courierCancelled;

        $estimatedProfit = $grossRevenue - $courierLoss;

        return [
            'gross_revenue' => round($grossRevenue, 2),
            'cancelled_value' => round($cancelledValue, 2),
            'returned_value' => round($returnedValue, 2),
            'avg_order_value' => round($avgOrderValue, 2),
            'courier_loss' => round($courierLoss, 2),
            'ltv' => round($grossRevenue, 2),
            'estimated_profit' => round($estimatedProfit, 2),
            'profit_per_order' => $summary['total_orders'] > 0
                ? round($estimatedProfit / $summary['total_orders'], 2)
                : 0,
        ];
    }

    protected function buildRiskSummary(array $summary, array $financial): array
    {
        $score = 50;

        // Positive
        if ($summary['success_rate'] >= 85) {
            $score += 20;
        } elseif ($summary['success_rate'] >= 70) {
            $score += 10;
        } elseif ($summary['success_rate'] < 50) {
            $score -= 15;
        }

        if ($summary['delivered_orders'] >= 5) {
            $score += 10;
        }

        if ($summary['consecutive_deliveries'] >= 3) {
            $score += 10;
        }

        if (($summary['days_since_last_successful_delivery'] ?? 9999) <= 90) {
            $score += 5;
        }

        if ($financial['ltv'] >= 20000) {
            $score += 10;
        } elseif ($financial['ltv'] >= 10000) {
            $score += 5;
        }

        // Negative
        if ($summary['fake_orders'] >= 1) {
            $score -= 25;
        }

        if ($summary['no_answer_count'] >= 3) {
            $score -= 15;
        } elseif ($summary['no_answer_count'] >= 1) {
            $score -= 5;
        }

        if ($summary['real_return_rate'] >= 50) {
            $score -= 20;
        } elseif ($summary['real_return_rate'] >= 30) {
            $score -= 10;
        }

        if ($summary['consecutive_returns'] >= 2) {
            $score -= 15;
        }

        if ($summary['last_order_status'] === 'cancelled') {
            $score -= 10;
        }

        if ($summary['used_names_count'] >= 3) {
            $score -= 10;
        } elseif ($summary['used_names_count'] === 2) {
            $score -= 5;
        }

        $score = max(0, min(100, $score));

        $level = match (true) {
            $score >= 80 => 'Very Safe',
            $score >= 65 => 'Good',
            $score >= 45 => 'Medium Risk',
            $score >= 25 => 'High Risk',
            default => 'Very High Risk',
        };

        return [
            'risk_score' => $score,
            'risk_level' => $level,
        ];
    }

    protected function buildPrediction(array $summary, array $risk): array
    {
        $probability = $risk['risk_score'];

        if ($summary['recent_30_day_orders'] === 0 && $summary['total_orders'] > 0) {
            $probability -= 5;
        }

        if ($summary['consecutive_returns'] >= 2) {
            $probability -= 10;
        }

        if ($summary['consecutive_deliveries'] >= 3) {
            $probability += 5;
        }

        if ($summary['fake_orders'] >= 1) {
            $probability -= 10;
        }

        if ($summary['no_answer_count'] >= 2) {
            $probability -= 10;
        }

        $probability = max(0, min(100, $probability));

        $confidence = match (true) {
            $summary['total_orders'] >= 10 => 'High',
            $summary['total_orders'] >= 4 => 'Medium',
            default => 'Low',
        };

        return [
            'delivery_probability' => round($probability, 2),
            'confidence' => $confidence,
        ];
    }

    protected function buildSegment(array $summary, array $financial, array $risk): string
    {
        if ($risk['risk_score'] < 25 || $summary['fake_orders'] >= 2) {
            return 'Blacklisted Candidate';
        }

        if ($financial['ltv'] >= 20000 && $summary['success_rate'] >= 80 && $summary['delivered_orders'] >= 5) {
            return 'Premium Customer';
        }

        if ($summary['success_rate'] >= 70 && $summary['delivered_orders'] >= 3) {
            return 'Good Customer';
        }

        if ($summary['real_return_rate'] >= 40 || $summary['consecutive_returns'] >= 2) {
            return 'Risky Customer';
        }

        if ($summary['total_orders'] <= 2) {
            return 'New Customer';
        }

        return 'Regular Customer';
    }

    protected function buildRecommendation(array $prediction, array $summary): array
    {
        $prob = $prediction['delivery_probability'];

        if ($prob >= 80) {
            return [
                'label' => 'Safe to Dispatch',
                'color' => 'success',
                'message' => 'Strong history and low operational risk.',
            ];
        }

        if ($prob >= 60) {
            return [
                'label' => 'Dispatch After Confirmation',
                'color' => 'warning',
                'message' => 'Looks usable, but confirm once more before dispatch.',
            ];
        }

        if ($prob >= 40) {
            return [
                'label' => 'Manual Review Required',
                'color' => 'warning',
                'message' => 'Verify address, availability, and recent behavior before dispatch.',
            ];
        }

        return [
            'label' => 'High Risk – Hold or Reconfirm',
            'color' => 'danger',
            'message' => 'Risk is high. Hold shipment or do strong reconfirmation.',
        ];
    }
}