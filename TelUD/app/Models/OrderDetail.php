<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class OrderDetail extends Model
{
    protected $table = 'order_details';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'quantity',
        'order_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

}