<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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

    /**
     * RELASI KE PENJUAL
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviews()
{
    // Kita paksa sebutin 'product_id' karena di DB lo namanya itu
    return $this->hasMany(Review::class, 'product_id');
}

}
