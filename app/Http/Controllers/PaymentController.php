<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Initiate payment securely
     */
    public function initiatePayment(Request $request)
    {
        $validated = $request->validate([
            'plan' => 'required|in:Starter,Growth,Scale',
            'email' => 'required|email',
            'phone' => 'required|regex:/^[0-9\-\+\(\)\s]*$/',
            'first_name' => 'required|string|min:2',
        ]);

        $amounts = [
            'Starter' => 5000,
            'Growth' => 10000,
            'Scale' => 20000,
        ];

        $amount = $amounts[$validated['plan']] ?? null;

        if (!$amount) {
            return response()->json(['error' => 'Invalid plan'], 422);
        }

        // Build secure payment payload
        $payment = [
            'sandbox' => config('services.payhere.sandbox', true),
            'merchant_id' => config('services.payhere.merchant_id'),
            'order_id' => 'ORD' . uniqid(),
            'items' => $validated['plan'],
            'amount' => $amount,
            'currency' => 'LKR',
            'first_name' => $validated['first_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'country' => 'Sri Lanka',
            'hash' => $this->generatePayHereHash(
                config('services.payhere.merchant_id'),
                'ORD' . uniqid(),
                $amount
            ),
        ];

        return response()->json($payment);
    }

    /**
     * Generate secure hash for PayHere
     */
    private function generatePayHereHash($merchantId, $orderId, $amount)
    {
        $hash = md5(
            $merchantId . $orderId . $amount .
            config('services.payhere.merchant_secret')
        );
        return strtoupper($hash);
    }

    /**
     * Handle PayHere callback
     */
    public function handleCallback(Request $request)
    {
        // Verify PayHere signature
        // Log payment status
        // Update database

        return response()->json(['status' => 'success']);
    }
}
