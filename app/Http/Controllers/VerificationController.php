<?php

namespace App\Http\Controllers;

use App\Models\Order;

class VerificationController extends Controller
{

    public function queue()
    {

        $orders = Order::with('customer')
            ->whereIn('recommended_action', [
                'call_verify',
                'manual_review',
                'block_cod'
            ])
            ->where('delivery_status', 'pending')
            ->orderBy('order_date','desc')
            ->get();

        return response()->json($orders);

    }
    public function verifyOrder(Request $request, $id)
    {

        $order = Order::findOrFail($id);

        $order->update([
            'verification_status' => $request->result
        ]);

        return response()->json([
            'message' => 'Verification updated'
        ]);

    }
}