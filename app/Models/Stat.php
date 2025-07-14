<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    protected $fillable = [
        'player_id',
        'version',
        'GP',
        'TP',
        'kick',
        'body',
        'control',
        'guard',
        'speed',
        'stamina',
        'guts',
        'freedom'
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function getStatsByGameAttribute()
    {
        return $this->stats->groupBy('game');
    }
}
