<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteResult extends Model
{
    protected $fillable = [
        'vote_id',
        'vote_option_id',
        'user_id',
    ];
}
