<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProductController extends Controller
{
    /**
     * ===============================
     * MARKETPLACE (BUYER)
     * ===============================
     */
    public function index(Request $request)
    {
        $products = Product::with('seller')
            ->where('user_id', '!=', Auth::id())
            ->when($request->search, fn ($q) =>
                $q->where('name', 'like', '%' . $request->search . '%')
            )
            ->when($request->category, fn ($q) =>
                $q->where('category', $request->category)
            )
            ->when($request->min_price, fn ($q) =>
                $q->where('price', '>=', $request->min_price)
            )
            ->when($request->max_price, fn ($q) =>
                $q->where('price', '<=', $request->max_price)
            )
            ->when($request->product_condition, fn ($q) =>
                $q->whereIn('product_condition', $request->product_condition)
            )
            ->latest()
            ->get();

        return view('deals.index', compact('products'));
    }

    /**
     * ===============================
     * SELLER PAGE
     * ===============================
     */
    public function sellerStore(User $user)
    {
        $products = Product::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('seller.store', [
            'seller'   => $user,
            'products' => $products,
        ]);
    }

    /**
     * ===============================
     * PRODUK MILIK USER LOGIN
     * ===============================
     */
    public function myProducts()
    {
        $products = Product::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('products.my-products', compact('products'));
    }

    /**
     * ===============================
     * CREATE
     * ===============================
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * ===============================
     * STORE
     * ===============================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string',
            'category'          => 'required|string',
            'description'       => 'required|string',
            'price'             => 'required|numeric',
            'stock'             => 'required|integer|min:0',
            'product_condition' => 'required|in:baru,bekas',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['user_id'] = Auth::id();

        Product::create($validated);

        return redirect()
            ->route('products.mine')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * ===============================
     * SHOW (DETAIL PRODUK) ✅ FIX
     * ===============================
     */
    public function show(Product $product)
    {
        $product->load('seller');

        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->latest()
            ->take(6)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * ===============================
     * EDIT
     * ===============================
     */
    public function edit(Product $product)
    {
        abort_if($product->user_id !== Auth::id(), 403);

        return view('products.edit', compact('product'));
    }

    /**
     * ===============================
     * UPDATE
     * ===============================
     */
    public function update(Request $request, Product $product)
    {
        abort_if($product->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'name'              => 'required|string',
            'category'          => 'required|string',
            'description'       => 'required|string',
            'price'             => 'required|numeric',
            'stock'             => 'required|integer|min:0',
            'product_condition' => 'required|in:baru,bekas',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()
            ->route('products.mine')
            ->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * ===============================
     * DELETE
     * ===============================
     */
    public function destroy(Product $product)
    {
        abort_if($product->user_id !== Auth::id(), 403);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('products.mine')
            ->with('success', 'Produk berhasil dihapus');
    }
}
