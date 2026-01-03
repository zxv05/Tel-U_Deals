<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'));

/*
|--------------------------------------------------------------------------
| MIDTRANS CALLBACK (PUBLIC - WAJIB TANPA AUTH)
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
    | DASHBOARD / MARKETPLACE
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/deals', [DashboardController::class, 'deals'])
    ->name('deals');


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
    | BUY NOW (LANGSUNG BUAT ORDER)
    |--------------------------------------------------------------------------
    */
    Route::post('/buy-now', [OrderController::class, 'buyNow'])
        ->name('orders.buyNow')
        ->middleware('auth');

    /*
    |--------------------------------------------------------------------------
    | SELLER - PRODUK SAYA
    |--------------------------------------------------------------------------
    */
    Route::get('/my-products', [ProductController::class, 'myProducts'])
        ->name('products.mine');

    Route::get('/products/create', [ProductController::class, 'create'])
        ->name('products.create');

    Route::post('/products', [ProductController::class, 'store'])
        ->name('products.store');

    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
        ->name('products.edit');

    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->name('products.update');

    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->name('products.destroy');

    /*
    |--------------------------------------------------------------------------
    | TRANSAKSI / ORDERS
    |--------------------------------------------------------------------------
    */
    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
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

require __DIR__.'/auth.php';
