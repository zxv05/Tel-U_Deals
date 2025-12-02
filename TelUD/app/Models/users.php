<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'IdUser';

    protected $fillable = [
        'Nama',
        'Email',
        'Password',
        'Role',
    ];

    protected $hidden = [
        'Password',
    ];

    protected $casts = [
        'Password' => 'hashed',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'fk_user', 'IdUser');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'fk_User', 'IdUser');
    }
}