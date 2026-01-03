<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function midtransCallback(Request $request)
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        $notif = new \Midtrans\Notification();

        $order = Order::where('order_id', $notif->order_id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if (in_array($notif->transaction_status, ['capture', 'settlement'])) {

            $order->update([
                'status' => 'paid',
                'payment_status' => 'paid',
            ]);

            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'amount' => $notif->gross_amount,
                    'status' => 'paid',
                    'payment_date' => now()
                ]
            );
        }

        if (in_array($notif->transaction_status, ['cancel', 'expire', 'deny'])) {
            $order->update([
                'status' => 'failed',
                'payment_status' => 'failed',
            ]);
        }

        return response()->json(['message' => 'OK']);
    }
}
