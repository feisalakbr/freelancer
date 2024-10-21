<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;

class PaymentController extends Controller
{
    public function pay(Order $order)
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $uniqueOrderId = $order->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $uniqueOrderId,
                'gross_amount' => $order->service->price,
            ],
            'customer_details' => [
                'first_name' => $order->user_buyer->name,
                'email' => $order->user_buyer->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('pages.dashboard.request.payment', compact('snapToken', 'order'));
    }
}
