<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'vote_id',
        'user_id',
        'label',
    ];

    // Relasi balik ke Vote
    public function vote()
    {
        return $this->belongsTo(Vote::class);
    }

    // Relasi ke User (kandidat)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function results()
    {
        return $this->hasMany(VoteResult::class);
    }
}
