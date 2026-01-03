<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * DASHBOARD KOSONG
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * TEL-U DEALS (MARKETPLACE)
     */
    public function deals(Request $request)
    {
        $products = Product::where('user_id', '!=', Auth::id())

            // 🔍 SEARCH NAMA PRODUK
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })

            // 🗂 FILTER KATEGORI
            ->when($request->category && $request->category !== 'Semua', function ($query) use ($request) {
                $query->where('category', $request->category);
            })

            // 💸 RANGE HARGA MIN
            ->when($request->min_price, function ($query) use ($request) {
                $query->where('price', '>=', $request->min_price);
            })

            // 💸 RANGE HARGA MAX
            ->when($request->max_price, function ($query) use ($request) {
                $query->where('price', '<=', $request->max_price);
            })

            ->latest()
            ->get();

        return view('deals.index', compact('products'));
    }
}
