<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable

{
    use HasFactory, Notifiable;

    protected $guard = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function donor()
    {
        return $this->hasMany(Donor::class);
    }
}
