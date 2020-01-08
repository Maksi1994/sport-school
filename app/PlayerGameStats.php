<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerGameStats extends Model
{
    public $timestamps = true;
    protected $guarded = [];
    public $table = 'games_stats';

    public function player() {
      return $this->belongsTo(Player::class);
    }

    public function game() {
        return $this->belongsTo(Game::class);
    }
}
