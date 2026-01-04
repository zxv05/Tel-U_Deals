<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AddressController;

use App\Models\Product;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'));

/*
|--------------------------------------------------------------------------
| MIDTRANS CALLBACK (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::post('/payment/midtrans-callback', [PaymentController::class, 'midtransCallback'])
    ->name('midtrans.callback');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | TEL-U DEALS / MARKETPLACE
    |--------------------------------------------------------------------------
    */
    Route::get('/deals', [ProductController::class, 'index'])
        ->name('deals');

    /*
    |--------------------------------------------------------------------------
    | SELLER - PRODUK SAYA
    |--------------------------------------------------------------------------
    */
    Route::get('/my-products', [ProductController::class, 'myProducts'])
        ->name('products.mine');

    /*
    |--------------------------------------------------------------------------
    | PRODUCT CREATE (HARUS DI ATAS {product})
    |--------------------------------------------------------------------------
    */
    Route::get('/products/create', [ProductController::class, 'create'])
        ->name('products.create');

    Route::post('/products', [ProductController::class, 'store'])
        ->name('products.store');

    /*
    |--------------------------------------------------------------------------
    | PRODUCT DETAIL
    |--------------------------------------------------------------------------
    */
    Route::get('/products/{product}', [ProductController::class, 'show'])
        ->name('products.show');

    /*
    |--------------------------------------------------------------------------
    | PRODUCT EDIT / UPDATE / DELETE
    |--------------------------------------------------------------------------
    */
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
        ->name('products.edit');

    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->name('products.update');

    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->name('products.destroy');

    /*
    |--------------------------------------------------------------------------
    | CART
    |--------------------------------------------------------------------------
    */
    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart', [CartController::class, 'store'])
        ->name('cart.store');

    Route::put('/cart/{cart}', [CartController::class, 'update'])
        ->name('cart.update');

    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])
        ->name('cart.destroy');

    Route::post('/cart/checkout', [CartController::class, 'checkout'])
        ->name('cart.checkout');

    /*
    |--------------------------------------------------------------------------
    | BUY NOW
    |--------------------------------------------------------------------------
    */
    Route::post('/buy-now', [OrderController::class, 'buyNow'])
        ->name('orders.buyNow');

    /*
    |--------------------------------------------------------------------------
    | ORDERS & HISTORY (RATING SYSTEM INCLUDED)
    |--------------------------------------------------------------------------
    */
    // 🛒 Riwayat List
    Route::get('/orders/history', [OrderController::class, 'history'])
        ->name('orders.history');

    // 📄 Detail Riwayat (Invoice & Rating Page)
    Route::get('/orders/history/{order}', [OrderController::class, 'detailHistory'])
        ->name('orders.detail');

    // ⭐ Simpan Rating
    Route::post('/orders/review', [OrderController::class, 'storeReview'])
        ->name('orders.review');

    // 💳 Payment Page (Midtrans)
    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');

    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    /*
    |--------------------------------------------------------------------------
    | PROFILE (FIXED)
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])
        ->name('profile.avatar');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | ADDRESS (PROFILE)
    |--------------------------------------------------------------------------
    */
    Route::post('/addresses', [AddressController::class, 'store'])
        ->name('addresses.store');

    Route::put('/addresses/{address}', [AddressController::class, 'update'])
        ->name('addresses.update');

    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])
        ->name('addresses.destroy');

});

/*
|--------------------------------------------------------------------------
| MAP API (OPTIONAL)
|--------------------------------------------------------------------------
*/
Route::get('/map/products', function (Request $request) {
    $latitude  = $request->latitude;
    $longitude = $request->longitude;
    $radius    = $request->radius ?? 10;

    return Product::select('*')
        ->selectRaw(
            "(6371 * acos(
                cos(radians(?)) *
                cos(radians(latitude)) *
                cos(radians(longitude) - radians(?)) +
                sin(radians(?)) *
                sin(radians(latitude))
            )) AS distance",
            [$latitude, $longitude, $latitude]
        )
        ->having('distance', '<=', $radius)
        ->orderBy('distance')
        ->get();
});

/*
|--------------------------------------------------------------------------
| SELLER STORE
|--------------------------------------------------------------------------
*/
Route::get('/seller/{user}', [ProductController::class, 'sellerStore'])
    ->name('seller.store');

require __DIR__ . '/auth.php';