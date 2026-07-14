<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'mobile_number',
        'otp',
        'profile_image',
        'mobile_verified_at',
        'password',
        
    ];

    protected $hidden = [
        'password',
        'otp',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'mobile_verified_at' => 'datetime',
    ];
}
