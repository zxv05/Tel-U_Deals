<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * Nama tabel (opsional, tapi aman)
     */
    protected $table = 'users';

    /**
     * Primary Key custom
     */
    protected $primaryKey = 'IdUser';

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
        ];
    }
}
