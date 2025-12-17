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
            'fk_user' => 3,
            'fk_product' => $product->ProductID,
            'quantity' => 2,
            'total_price' => $product->HargaProduct * 2,
        ]);

    }
}
