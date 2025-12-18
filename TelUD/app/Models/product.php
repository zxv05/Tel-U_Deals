<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product'; // Nama tabel singular
    protected $primaryKey = 'ProductID'; // PK Custom
    
    // Biar bisa mass assignment
    protected $fillable = [
        'user_id', 'nama_barang', 'kategori_id', 'harga_product', 'product_detail'
    ];

    // Relasi ke User
    public function seller()
    {
        return $this->belongsTo(User::class, 'fk_user', 'IdUser');
    }

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'fk_kategori', 'KategoriID');
    }
}