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
        'name',     // 🟢 TAMBAHKAN INI
        'email',    // 🟢 PASTIKAN INI JUGA ADA
        'password',
        'avatar',   // Tambahkan ini jika nanti ingin upload foto profil lewat mass assignment
    ];
}
