<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{

    
    protected $primaryKey = 'OrderDetailsID';

    protected $fillable = [
        'Date',
        'fk_product',
        'fk_order'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'fk_order', 'OrderID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'fk_product', 'ProductID');
    }

}