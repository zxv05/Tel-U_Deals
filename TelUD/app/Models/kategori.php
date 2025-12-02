<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Kategori extends Model
{
    protected $table = 'kategori';
    
    protected $primaryKey = 'KategoriID';

    protected $fillable = ['NamaKategori'];


    public function products()
    {
        return $this->hasMany(Product::class, 'fk_kategori', 'KategoriID');
    }
}