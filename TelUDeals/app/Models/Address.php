<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';
    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone',
        'label',
        'full_address',
        'courier_note',
        'latitude',
        'longitude',
        'is_primary',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orders()
{
    return $this->hasMany(Order::class);
}

}
