<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kategori::create([
            'nama_kategori' => 'Furnitur',
            
        ]);

        Kategori::create([
            'nama_kategori' => 'Pakaian',
            
        ]);

        Kategori::create([
            'nama_kategori' => 'Elektronik',
            
        ]);
    }
}
