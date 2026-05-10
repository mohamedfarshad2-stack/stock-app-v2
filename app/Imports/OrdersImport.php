<?php

namespace App\Imports;

use App\Models\Order;
use App\Models\Customer;
use App\Models\ProductCategory;
use App\Models\Channel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class OrdersImport implements ToCollection, WithChunkReading
{
    public function collection(Collection $rows)
    {
        $rows->shift(); // remove header row

        foreach ($rows as $row) {

            // 🔹 Map Excel columns
            $order_date = $row[0] ?? null;
            $name = trim($row[1] ?? '');
            $address = trim($row[2] ?? '');
            $city = trim($row[3] ?? '');
            $phone = trim($row[4] ?? '');
            $alternate_phone = trim($row[5] ?? '');
            $product_name = trim($row[6] ?? '');
            $category = $row[7] ?? null;
            $sku = trim($row[8] ?? '');
            $quantity = !empty($row[9]) ? (int) $row[9] : 1;
            $status = strtoupper(trim($row[10] ?? ''));
            $amount = (float) ($row[11] ?? 0);
            $channel_name = trim($row[12] ?? '');

            // ❌ Skip bad rows
            // if (!$phone || !$amount) {
            //     continue;
            // }
             if (!$phone ) {
    //               logger('Skipped - No phone', [
    //     'row' => $row->toArray(),
    // ]);
    // continue;
                continue;
            }

            // 🔥 Normalize phone (VERY IMPORTANT)
            // $phone = preg_replace('/[^0-9]/', '', $phone);

            // if (str_starts_with($phone, '0')) {
            //     $phone = '94' . substr($phone, 1);
            // } elseif (!str_starts_with($phone, '94')) {
            //     $phone = '94' . $phone;
            // }
            $phone = preg_replace('/[^0-9]/', '', $phone);
            $alternate_phone = preg_replace('/[^0-9]/', '', $alternate_phone);

            if (str_starts_with($phone, '0')) {
                $phone = '94' . substr($phone, 1);
            } elseif (!str_starts_with($phone, '94')) {
                $phone = '94' . $phone;
            }

            // if ($alternate_phone) {
            //     if (str_starts_with($alternate_phone, '0')) {
            //         $alternate_phone = '94' . substr($alternate_phone, 1);
            //     } elseif (!str_starts_with($alternate_phone, '94')) {
            //         $alternate_phone = '94' . $alternate_phone;
            //     }
            // }

            // if (strlen($phone) < 10) {
            //     continue; // skip invalid
            // }

            // 🔹 SAFE Customer Handling (NO Postgres crash)
            $customer = Customer::where('normalized_phone', $phone)->first();
            // $customer = Customer::where('normalized_phone', $phone)
            //     ->orWhere('normalized_phone', $alternate_phone)
            //     ->orWhere('alternate_phone', $phone)
            //     ->orWhere('alternate_phone', $alternate_phone)
            //     ->first();

            if (!$customer) {
                try {
                    $customer = Customer::create([
                        'name' => $name,
                        'phone' => $phone,
                        'normalized_phone' => $phone,
                        'alternate_phone' => $alternate_phone,
                        'city' => $city,
                    ]);
                } catch (\Exception $e) {
                    $customer = Customer::where('normalized_phone', $phone)->first();
                }
            }

            // 🔹 Channel mapping
            $channel = Channel::where('name', $channel_name)->first();
            $channel_id = $channel ? $channel->id : 1;
            
            $ProductCategory = ProductCategory::where('name', $category)->first();
            $ProductCategory_id = $ProductCategory ? $ProductCategory->id : 1;



            // 🔹 Status mapping
            $delivery_status = match ($status) {
                'D' => 'delivered',
                'R' => 'cancelled',
                default => 'pending',
            };

            // 🔹 Date parsing (Excel safe)
            try {
                if (is_numeric($order_date)) {
                    $order_date = Date::excelToDateTimeObject($order_date);
                } else {
                    $order_date = Carbon::createFromFormat('d/m/Y', $order_date);
                }
            } catch (\Exception $e) {
                // $order_date = now();
            }
  
            // 🔹 Prevent duplicate orders
            $exists = Order::where('customer_id', $customer->id)
                // ->where('total_amount', $amount)
                ->whereDate('order_date', $order_date)
                ->exists();

            if ($exists) {
                // continue;
                    logger('Skipped - Duplicate', [
        'customer_id' => $customer->id,
        'date' => $order_date,
        'amount' => $amount,
        'sku' => $sku,
    ]);
    continue;
            }

            // 🔹 Create Order
            $order = Order::create([
                'customer_id' => $customer->id,
                'channel_id' => $channel_id,
                'order_date' => $order_date,
                'city' => $city,
                'address' => $address,
                'total_amount' => $amount,
                'delivery_status' => $delivery_status,
            ]);

            // 🔹 Create Item
            $order->items()->create([
                'product_name' => $product_name ?: $sku,
                'product_category_id' => $ProductCategory_id,
                'sku' => $sku,
                'quantity' => $quantity,
                'selling_price' => $amount,
                'total_price' => $amount * $quantity,
            ]);
        }
    }

    // 🔥 Prevent transaction crash
    public function chunkSize(): int
    {
        return 100;
    }
}