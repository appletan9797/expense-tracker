<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    const USERNAME_FIELD = 'user_name';

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_name',
        'password'
    ];

    protected $hidden = [
        'password'
    ];
}
