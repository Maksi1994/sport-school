<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
  protected $guarded = [];
  public $timestamps = true;

  public function players() {
      return $this->belongsToMany(Player::class, 'season_player', 'season_id', 'player_id');
  }

  public function teams() {
      return $this->belongsToMany(Team::class, 'season_player', 'season_id', 'team_id');
  }


}
