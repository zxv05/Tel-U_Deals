<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;

// 1. Routes untuk resources 'users'
Route::resource('users', UserController::class);

// 2. Routes untuk resources 'kategori'
Route::resource('kategori', KategoriController::class);

// 3. Routes untuk resources 'product'
// Kita bisa menggunakan 'only' jika kita hanya ingin sebagian aksi, misalnya:
// Route::resource('products', ProductController::class)->only(['index', 'show']);
Route::resource('products', ProductController::class);

// 4. Routes untuk resources 'orders'
Route::resource('orders', OrderController::class);

// 5. Routes untuk resources 'order_details' (Nested resource, misalnya)
Route::resource('order_details', OrderDetailController::class);

// Contoh Route Khusus: Mendapatkan produk berdasarkan kategori tertentu
Route::get('kategori/{kategoriId}/products', [KategoriController::class, 'getProducts']);