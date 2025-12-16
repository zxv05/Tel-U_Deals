<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use  HasFactory, Notifiable, HasRoles, HasApiTokens;

    protected $primaryKey = 'IdUser';


    /**
     * Primary Key custom
     */
    

    /**
     * Karena pakai bigIncrements
     */
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * KOLOM YANG BOLEH DI-INSERT (INI PALING PENTING)
     */
    protected $fillable = [
        'Nama',
        'Email',
        'Password',
        'Role',
    ];

    /**
     * Sembunyikan password saat serialize
     */
    protected $hidden = [
        'Password',
        'remember_token',
    ];

    /**
     * Cast tipe data
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'Password' => 'hashed',
        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    
}
