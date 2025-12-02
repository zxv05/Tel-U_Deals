<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';

    protected $primaryKey = 'ProductID';

    protected $fillable = [
        'fk_user',
        'NamaBarang',
        'fk_kategori',
        'HargaProduct',
        'ProductDetail'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'fk_user', 'IdUser');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'fk_kategori', 'KategoriID');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'fk_product', 'ProductID');
    }
}