<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;
use App\Models\OrderDetail;
use App\Models\User; 
use App\Models\Review; // Tambahkan ini bang

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'category',
        'description',
        'price',
        'stock',
        'product_condition',
        'image',
    ];

    // ================= RELASI KE SELLER =================
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ================= RELASI LAIN =================
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // ================= RELASI KE REVIEW =================
    /**
     * Relasi ke ulasan/rating produk
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Menghitung rata-rata rating produk
     * Mengembalikan 0 jika belum ada rating
     */
    public function averageRating()
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
}