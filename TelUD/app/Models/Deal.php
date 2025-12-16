<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'harga',
        'diskon',
        'gambar',
    ];
}
