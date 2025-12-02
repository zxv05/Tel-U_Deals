<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    protected $primaryKey = 'OrderID';

    protected $fillable = ['fk_User'];

    public function user()
    {
        return $this->belongsTo(User::class, 'fk_User', 'IdUser');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'fk_order', 'OrderID');
    }
}