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
            'user_id' => 2,
            'nama_barang' => 'Produk 1',
            'kategori_id' => 1,
            'harga_product' => 100000,
            'product_detail' => 'Detail Produk'
        ]);

        Product::create([
            'user_id' => 2,
            'nama_barang' => 'Produk 2',
            'kategori_id' => 2,
            'harga_product' => 150000,
            'product_detail' => 'Detail Produk'
        ]);

        Product::create([
            'user_id' => 2,
            'nama_barang' => 'Produk 3',
            'kategori_id' => 3,
            'harga_product' => 200000,
            'product_detail' => 'Detail Produk'
        ]);

    }
}
