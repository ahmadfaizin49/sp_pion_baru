<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    protected $fillable = [
        'user_id',
        'device_id',
        'device_name',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    /**
     * Relasi balik ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id', 'id');
    }
}
