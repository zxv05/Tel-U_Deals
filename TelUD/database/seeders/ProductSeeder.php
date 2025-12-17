<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat data dummy produk
        Product::create([
            'fk_user' => 1,
            'NamaBarang' => 'Produk 1',
            'fk_kategori' => 1,
            'HargaProduct' => 100000,
            'ProductDetail' => 'Detail Produk'
        ]);

        Product::create([
            'fk_user' => 1,
            'NamaBarang' => 'Produk 2',
            'fk_kategori' => 2,
            'HargaProduct' => 150000,
            'ProductDetail' => 'Detail Produk'
        ]);

        Product::create([
            'fk_user' => 1,
            'NamaBarang' => 'Produk 3',
            'fk_kategori' => 3,
            'HargaProduct' => 200000,
            'ProductDetail' => 'Detail Produk'
        ]);

    }
}
