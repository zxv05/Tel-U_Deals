<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * ===============================
     * MARKETPLACE (BUYER)
     * ===============================
     */
    public function index(Request $request)
    {
        $products = Product::query()

            // kalau login, sembunyikan produk sendiri
            ->when(Auth::check(), function ($q) {
                $q->where('user_id', '!=', Auth::id());
            })

            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })

            ->when($request->category, function ($q) use ($request) {
                $q->where('category', $request->category);
            })

            ->when($request->min_price, function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            })

            ->when($request->max_price, function ($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            })

            // FIX: whereIn hanya kalau array
            ->when(is_array($request->product_condition), function ($q) use ($request) {
                $q->whereIn('product_condition', $request->product_condition);
            })

            ->latest()
            ->get();

        return view('deals.index', compact('products'));
    }

    /**
     * ===============================
     * SELLER - PRODUK SAYA
     * ===============================
     */
    public function myProducts()
    {
        $products = Product::where('user_id', Auth::id())->get();

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
        $request->validate([
            'name'              => 'required|string|max:255',
            'category'          => 'required|string|max:100',
            'description'       => 'required|string',
            'price'             => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'product_condition' => 'required|in:baru,bekas',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'user_id'           => Auth::id(),
            'name'              => $request->name,
            'category'          => $request->category,
            'description'       => $request->description,
            'price'             => $request->price,
            'stock'             => $request->stock,
            'product_condition' => $request->product_condition,
            'image'             => $imagePath,
        ]);

        return redirect()
            ->route('products.mine')
            ->with('success', 'Produk berhasil ditambahkan');
    }
    public function __construct()
{
    $this->middleware('auth')->except([
        'index',
        'show',
        'sellerStore'
    ]);
}

    /**
     * ===============================
     * SHOW (DETAIL PRODUK)
     * ===============================
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->when(Auth::check(), function ($q) {
                $q->where('user_id', '!=', Auth::id());
            })
            ->limit(5)
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

        $request->validate([
            'name'              => 'required|string|max:255',
            'category'          => 'required|string|max:100',
            'description'       => 'required|string',
            'price'             => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'product_condition' => 'required|in:baru,bekas',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update($request->only([
            'name',
            'category',
            'description',
            'price',
            'stock',
            'product_condition'
        ]));

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

    /**
     * ===============================
     * SELLER STORE
     * ===============================
     */
    public function sellerStore(User $user)
    {
        $products = Product::where('user_id', $user->id)->latest()->get();

        return view('Seller.store', compact('user', 'products'));
    }
}
