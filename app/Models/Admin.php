<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;
    protected $table = 'admins';

    protected $fillable = [
        'name',
        'email',
        'mobile_number',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts =[
        'password' => 'hashed',
    ];
}
