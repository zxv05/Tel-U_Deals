<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products'; // Nama tabel singular
    protected $primaryKey = 'id'; // PK Custom
    
    // Biar bisa mass assignment
    protected $fillable = [
        'user_id', 'nama_barang', 'kategori_id', 'harga_product', 'product_detail','stok'
    ];

    // Relasi ke User
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }
}