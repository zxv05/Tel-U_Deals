<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function deals(Request $request)
    {
        try {
            $products = Product::where('user_id', '!=', Auth::id())
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

                // KONDISI (FIXED TYPO & ARRAY CHECK)
                ->when($request->product_condition, function ($q) use ($request) {
                    $condition = (array) $request->product_condition;
                    $q->whereIn('product_condition', $condition);
                })

                ->latest()
                ->get();

            return view('deals.index', compact('products'));

        } catch (\Exception $e) {
            Log::error("Error di Deals: " . $e->getMessage());
            
            return back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }
}