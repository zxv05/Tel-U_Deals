<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * ===============================
     * MARKETPLACE (BUYER)
     * ===============================
     * Tampilkan produk dari USER LAIN
     */
    public function index(Request $request)
    {
        $products = Product::where('user_id', '!=', Auth::id())
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
            ->when($request->product_condition, function ($q) use ($request) {
                $q->whereIn('product_condition', $request->product_condition);
            })


            ->get();

        return view('deals.index', compact('products'));
    }

    /**
     * ===============================
     * SELLER PAGE
     * ===============================
     * Produk milik USER LOGIN
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
            'name'        => 'required',
            'category'    => 'required',
            'description' => 'required',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer|min:0',
            'product_condition'  => 'required|in:baru,bekas',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = $request->file('image')
            ? $request->file('image')->store('products', 'public')
            : null;

        Product::create([
            'user_id'     => Auth::id(),
            'name'        => $request->name,
            'category'    => $request->category,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'product_condition' => $request->product_condition,
            'image'       => $imagePath,
        ]);

        return redirect()->route('products.mine')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * ===============================
     * EDIT (OWNER ONLY)
     * ===============================
     */
    public function edit(Product $product)
    {
        abort_if($product->user_id !== Auth::id(), 403);

        return view('products.edit', compact('product'));
    }
    /**
     * ===============================
     * SHOW (DETAIL PRODUK)
     * ===============================
     */
   public function show($id)
{
    $product = Product::findOrFail($id);
    return view('products.show', compact('product'));
}


    /**
     * ===============================
     * UPDATE (OWNER ONLY)
     * ===============================
     */
    public function update(Request $request, Product $product)
    {
        abort_if($product->user_id !== Auth::id(), 403);

        $request->validate([
            'name'        => 'required',
            'category'    => 'required',
            'description' => 'required',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update($request->only(
            'name',
            'category',
            'description',
            'price',
            'stock',
            'product_condition'
        ));

        return redirect()->route('products.mine')
            ->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * ===============================
     * DELETE (OWNER ONLY)
     * ===============================
     */
    public function destroy(Product $product)
    {
        abort_if($product->user_id !== Auth::id(), 403);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.mine')
            ->with('success', 'Produk berhasil dihapus');
    }
}
