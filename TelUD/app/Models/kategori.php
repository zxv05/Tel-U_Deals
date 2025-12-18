<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    
    protected $primaryKey = 'id';

    protected $fillable = ['nama_kategori'];


    public function products()
    {
        return $this->hasMany(Product::class);
    }
}