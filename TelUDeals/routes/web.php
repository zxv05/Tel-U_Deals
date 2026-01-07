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

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (GUEST BOLEH AKSES)
|--------------------------------------------------------------------------
*/

// LANDING / DASHBOARD
Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

// MARKETPLACE
Route::get('/deals', [ProductController::class, 'index'])
    ->name('deals');

// DETAIL PRODUK
Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('products.show');

// SELLER STORE
Route::get('/seller/{user}', [ProductController::class, 'sellerStore'])
    ->name('seller.store');

/*
|--------------------------------------------------------------------------
| MIDTRANS CALLBACK (NO AUTH)
|--------------------------------------------------------------------------
*/
Route::post('/payment/midtrans-callback', [PaymentController::class, 'midtransCallback'])
    ->name('midtrans.callback');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | CART
    |--------------------------------------------------------------------------
    */
    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart', [CartController::class, 'store'])
        ->name('cart.store');

    Route::delete('/cart/{id}', [CartController::class, 'destroy'])
        ->name('cart.destroy');

    Route::post('/cart/checkout', [CartController::class, 'checkout'])
        ->name('cart.checkout');

    /*
    |--------------------------------------------------------------------------
    | ORDERS
    |--------------------------------------------------------------------------
    */
    Route::post('/buy-now', [OrderController::class, 'buyNow'])
        ->name('orders.buyNow');

    Route::get('/orders/history', [OrderController::class, 'history'])
        ->name('orders.history');

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');

    Route::get('/orders/history/{order}', [OrderController::class, 'detailHistory'])
        ->name('orders.detailHistory');

    Route::post('/orders/review', [OrderController::class, 'storeReview'])
        ->name('orders.review');
    
    Route::post('/orders/{order}/mark-done', [OrderController::class, 'markAsDone'])
        ->name('orders.markDone');
    Route::post('/orders/{order}/set-address', [OrderController::class, 'setAddress'])
        ->name('orders.setAddress');

    /*
    |--------------------------------------------------------------------------
    | PRODUCT CRUD (SELLER)
    |--------------------------------------------------------------------------
    */
    Route::get('/my-products', [ProductController::class, 'myProducts'])
        ->name('products.mine');

    Route::get('/products/create/new', [ProductController::class, 'create'])
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
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])
        ->name('profile.avatar');

    /*
    |--------------------------------------------------------------------------
    | ADDRESS
    |--------------------------------------------------------------------------
    */
    Route::post('/addresses', [AddressController::class, 'store'])
        ->name('addresses.store');

    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])
        ->name('addresses.destroy');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (LOGIN, REGISTER, LOGOUT)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
