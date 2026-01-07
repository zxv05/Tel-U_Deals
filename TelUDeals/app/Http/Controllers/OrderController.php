<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Address;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
{
    $this->middleware('auth');
}

    public function history()
    {
        $userId = Auth::id();

        $purchases = Order::where('user_id', $userId)
            ->with(['orderDetails.product.seller']) // Ditambahin seller
            ->latest()
            ->get();

        $sales = Order::whereHas('orderDetails.product', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['user', 'orderDetails.product'])
            ->latest()
            ->get();

        return view('orders.history', compact('purchases', 'sales'));
    }

    public function detailHistory(Order $order) 
{
    $userId = Auth::id();

    // Proteksi intip pesanan orang
    $isSeller = $order->orderDetails()->whereHas('product', function($q) use ($userId) {
        $q->where('user_id', $userId);
    })->exists();

    if ($order->user_id !== $userId && !$isSeller) {
        abort(403, 'Lu ngapain ngintip pesanan orang bang?');
    }

    $order->load(['reviews.user', 'orderDetails.product.seller']);

    // ================= MIDTRANS LOGIC =================
    $payment = $order->payments()->latest()->first();
    $snapToken = null;

    if ($order->payment_status === 'unpaid' && $order->user_id === $userId) {

        if (!$payment || !$payment->snap_token) {

            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_id,
                    'gross_amount' => (int) $order->total_price,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
                'callbacks' => [
                    'finish'   => route('orders.history'),
                    'unfinish' => route('orders.history'),
                    'error'    => route('orders.history'),
                ],
            ];

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);

                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    ['status' => 'pending', 'snap_token' => $snapToken]
                );
            } catch (\Exception $e) {
                $snapToken = null;
            }

        } else {
            $snapToken = $payment->snap_token;
        }
    }

    return view('orders.detail_history', compact('order', 'snapToken'));
}
public function show(Order $order)
{
    $userId = Auth::id();

    // === CEK ROLE BUYER / SELLER ===
    $isBuyer = $order->user_id === $userId;

    $isSeller = $order->orderDetails()
        ->whereHas('product', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->exists();

    // Kalau bukan buyer & bukan seller → TOLAK
    abort_if(! $isBuyer && ! $isSeller, 403);

    // === LOAD RELASI ===
    $order->load([
        'orderDetails.product',
        'orderDetails.product.reviews' => function ($q) use ($order) {
            $q->where('order_id', $order->id);
        },
        'payments'
    ]);

    // === DATA KHUSUS BUYER ===
    $addresses = collect();
    $snapToken = null;

    if ($isBuyer) {
        // Ambil alamat buyer
        $addresses = Address::where('user_id', $userId)->get();

        // Cari pembayaran terakhir
        $payment = $order->payments()->latest()->first();

        // MIDTRANS HANYA UNTUK BUYER
        if ($order->payment_status === 'unpaid') {
            if (! $payment || ! $payment->snap_token) {

                \Midtrans\Config::$serverKey = config('midtrans.server_key');
                \Midtrans\Config::$isProduction = config('midtrans.is_production');
                \Midtrans\Config::$is3ds = true;

                $params = [
                    'transaction_details' => [
                        'order_id' => $order->order_id,
                        'gross_amount' => (int) $order->total_price,
                    ],
                    'customer_details' => [
                        'first_name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                    ],
                    'callbacks' => [
                        'finish'   => route('orders.history'),
                        'unfinish' => route('orders.history'),
                        'error'    => route('orders.history'),
                    ],
                ];

                try {
                    $snapToken = \Midtrans\Snap::getSnapToken($params);

                    Payment::updateOrCreate(
                        ['order_id' => $order->id],
                        [
                            'status'     => 'pending',
                            'snap_token' => $snapToken
                        ]
                    );
                } catch (\Exception $e) {
                    $snapToken = null;
                }

            } else {
                $snapToken = $payment->snap_token;
            }
        }
    }

    return view('orders.show', compact(
        'order',
        'snapToken',
        'addresses',
        'isBuyer',
        'isSeller'
    ));
}
public function markAsDone(Order $order)
{
    $userId = auth::id();

    // hanya seller pemilik produk
    $isSeller = $order->orderDetails()
        ->whereHas('product', fn ($q) => $q->where('user_id', $userId))
        ->exists();

    abort_if(! $isSeller, 403);

    // pastikan sudah dibayar
    abort_if($order->payment_status !== 'paid', 403);

    $order->update([
        'status' => 'completed'
    ]);

    return back()->with('success', 'Pesanan ditandai selesai');
}
public function setAddress(Request $request, Order $order)
{
    // 🔐 pastikan order milik user
    if ($order->user_id !== auth::id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // ✅ validasi
    $request->validate([
        'address_id' => 'required|exists:addresses,id',
    ]);

    try {
        $order->update([
            'address_id' => $request->address_id,
        ]);

        return response()->json(['message' => 'Alamat berhasil disimpan']);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Alamat gagal disimpan',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function buyNow(Request $request)
    {
        // Validasi input produk dan jumlah
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Cek apakah stok mencukupi
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stok nggak cukup bang!');
        }

        DB::beginTransaction(); // Memulai transaksi database agar data konsisten
        try {
            $orderCode = 'ORD-' . strtoupper(uniqid()); // Generate kode order unik

            // 1. Buat data Order utama
            $order = Order::create([
                'order_id'       => $orderCode,
                'user_id'        => Auth::id(),
                'total_price'    => $product->price * $request->quantity,
                'status'         => 'pending',
                'payment_status' => 'unpaid',
            ]);

            // 2. Buat data Detail Order
            OrderDetail::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'price'      => $product->price,
                'quantity'   => $request->quantity,
            ]);

            // 3. Kurangi stok produk
            $product->decrement('stock', $request->quantity);

            DB::commit(); // Simpan perubahan secara permanen

            // Redirect ke halaman pembayaran dengan parameter order ID
            return redirect()->route('orders.show', ['order' => $order->id]);

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua perubahan jika terjadi error
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage()); // Tampilkan pesan error detail
        }
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'nullable|string|max:255'
        ]);

        $order = Order::findOrFail($request->order_id);
        
        if($order->user_id !== Auth::id()) {
            abort(403, 'Cuma pembeli yang bisa kasih rating bang.');
        }

        foreach($order->orderDetails as $detail) {
            Review::updateOrCreate(
                [
                    'order_id'   => $order->id, 
                    'product_id' => $detail->product_id,
                    'user_id'    => Auth::id()
                ],
                [
                    'rating'  => $request->rating,
                    'comment' => $request->comment
                ]
            );
        }

        return back()->with('success', 'Ulasan berhasil disimpan.');
    }

    // ... sisa function show dan buyNow tetap sama seperti kode lu ...
}