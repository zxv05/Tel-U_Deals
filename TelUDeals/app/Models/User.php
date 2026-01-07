<?php

namespace App\Models;
use App\Models\Address;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    public function addresses(): HasMany
    {
    
        return $this->hasMany(Address::class);
    }
    protected $fillable = [
    'name',
    'email',
    'password',
    'tanggal_lahir', // Tambahkan
    'phone',         // Tambahkan
    'avatar',
];
}
