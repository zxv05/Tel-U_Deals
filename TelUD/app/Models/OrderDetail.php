<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_details';
    protected $primaryKey = 'OrderDetailsID';
    protected $fillable = ['Date', 'fk_User', 'fk_product', 'fk_order'];
}