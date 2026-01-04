<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function midtransCallback(Request $request)
    {
        $orderId = $request->order_id;
        $status = $request->transaction_status;

        $order = Order::where('order_id', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan di database'], 404);
        }

        if (in_array($status, ['capture', 'settlement'])) {
            $order->update([
                'status' => 'processing', 
                'payment_status' => 'paid',
            ]);

            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'status' => 'paid',
                    'paid_at' => now(),
                ]
            );
        }

        if (in_array($status, ['cancel', 'expire', 'deny'])) {
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'failed',
            ]);

            Payment::updateOrCreate(
                ['order_id' => $order->id],
                ['status' => 'failed']
            );
        }

        return response()->json(['message' => 'Callback success']);
    }
}
