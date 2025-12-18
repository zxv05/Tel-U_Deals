<?php

namespace Database\Seeders;

use App\Models\Keranjang;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KeranjangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $product = Product::first(); // Ambil produk pertama
        Keranjang::create([
            'user_id' => 3,
            'product_id' => $product->id,
            'quantity' => 2,
            'total_price' => $product->harga_product * 2,
        ]);

    }
}
