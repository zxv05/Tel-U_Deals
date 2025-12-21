<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Models\Deal;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman welcome (public)


// Auth routes (register, login, dll)
require __DIR__.'/auth.php';

// Group routes yang harus login
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', function () {
        $deals = Deal::all();  // Ambil semua deals dari database
        return view('dashboard', compact('deals'));
    })->name('dashboard');
    Route::get('/dashboard', function () {
        $deals = Deal::all();  // Ambil semua deals dari database
        return view('dashboard', compact('deals'));
    })->name('dashboard');

    // Profile user
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Deals umum (tampil dan beli)
    Route::get('/deals', function () {
        return view('deals.index', [
            'deals' => Deal::all()
        ]);
    })->name('deals.index');

    Route::post('/deals/{id}/buy', [TransactionController::class, 'buy'])->name('deals.buy');

    // Marketplace (jual & beli)
    Route::get('/telu-deals', [MarketplaceController::class, 'index'])->name('marketplace.index');
    Route::get('/jual-barang', [MarketplaceController::class, 'create'])->name('marketplace.create');
    Route::post('/jual-barang', [MarketplaceController::class, 'store'])->name('marketplace.store');
    Route::post('/beli/{id}', [MarketplaceController::class, 'checkout'])->name('marketplace.checkout');

    // Group routes khusus admin
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        Route::get('/admin/deals', [DealController::class, 'index'])->name('admin.deals.index');
        Route::get('/admin/deals/create', [DealController::class, 'create'])->name('admin.deals.create');
        Route::post('/admin/deals', [DealController::class, 'store'])->name('admin.deals.store');
    });

    Route::post('keranjang/checkout', [KeranjangController::class, 'checkout'])->name('keranjang.checkout');
    Route::get('keranjang',[KeranjangController::class, 'index'])->name('keranjang.index');
    Route::resource('keranjang', KeranjangController::class);
    Route::delete('keranjang',[KeranjangController::class, 'destroy'])->name('keranjang.destroy');
    Route::post('/payment/midtrans-callback', [PaymentController::class, 'midtransCallback']);

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

});
