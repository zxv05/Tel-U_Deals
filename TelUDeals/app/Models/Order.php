<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Review; // Tambahin ini bang

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'total_price',
        'status',
        'payment_status'
    ];

    /**
     * Relasi ke User (Pembeli)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Detail Order (Produk apa aja yang dibeli)
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Relasi ke data Pembayaran
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * RELASI BARU: Menghubungkan Order dengan Review/Rating
     * Digunakan untuk menampilkan ulasan di halaman detail history
     */
    public function reviews()
    {
        return $this->hasMany(Review::class); // Tambahin ini buat nampung rating
    }
}