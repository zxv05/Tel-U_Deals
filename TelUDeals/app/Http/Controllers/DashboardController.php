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
    $products = Product::where('user_id', '!=', auth::id())

        // SEARCH
        ->when($request->search, function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        })

        // KATEGORI
        ->when($request->category, function ($q) use ($request) {
            $q->where('category', $request->category);
        })

        // HARGA MIN
        ->when($request->min_price, function ($q) use ($request) {
            $q->where('price', '>=', $request->min_price);
        })

        // HARGA MAX
        ->when($request->max_price, function ($q) use ($request) {
            $q->where('price', '<=', $request->max_price);
        })

        // 🔥 KONDISI (BARU / BEKAS) — INI YANG KEMARIN BIKIN ERROR
        ->when($request->produt_condition, function ($q) use ($request) {
            $q->whereIn('product_condition', (array) $request->product_condition);
        })

        ->latest()
        ->get();

    return view('deals.index', compact('products'));
}

}
