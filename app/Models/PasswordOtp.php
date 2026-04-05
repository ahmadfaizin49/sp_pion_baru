<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp',
        'reset_token',
        'expires_at',
        'used_at'
    ];
    
    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public $timestamps = true;
}
