<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'options_count' => 'integer',
        'results_count' => 'integer',
    ];


    // Relasi ke kandidat / options
    public function options()
    {
        return $this->hasMany(VoteOption::class);
    }

    public function results()
    {
        return $this->hasMany(VoteResult::class);
    }
}
