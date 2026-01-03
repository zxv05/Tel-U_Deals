<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;
use App\Models\OrderDetail;

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
    'image',
];

    public function orderDetails()
{
    return $this->hasMany(OrderDetail::class);
}
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
