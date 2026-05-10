<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderItem;

class DashboardController extends Controller
{

    public function summary()
    {

        $totalOrders = Order::count();

        $deliveredOrders = Order::where('delivery_status','delivered')->count();

        $returnedOrders = Order::where('delivery_status','returned')->count();

        $returnRate = 0;

        if($totalOrders > 0){
            $returnRate = round(($returnedOrders / $totalOrders) * 100,2);
        }

        return response()->json([

            'total_orders' => $totalOrders,
            'delivered_orders' => $deliveredOrders,
            'returned_orders' => $returnedOrders,
            'return_rate' => $returnRate

        ]);

    }
    public function riskyCustomers()
{

    $customers = Customer::where('risk_level','high')
        ->orWhere('risk_level','very_high')
        ->orderBy('trust_score')
        ->limit(10)
        ->get();

    return response()->json($customers);

}
public function trustedCustomers()
{

    $customers = Customer::where('risk_level','low')
        ->orderByDesc('trust_score')
        ->limit(10)
        ->get();

    return response()->json($customers);

}
public function ordersByChannel()
{

    $data = Order::selectRaw('channel_id, COUNT(*) as total')
        ->groupBy('channel_id')
        ->get();

    return response()->json($data);

}
public function returnsByCategory()
{

    $data = OrderItem::selectRaw('product_category_id, COUNT(*) as total')
        ->join('orders','orders.id','=','order_items.order_id')
        ->where('orders.delivery_status','returned')
        ->groupBy('product_category_id')
        ->get();

    return response()->json($data);

}

}