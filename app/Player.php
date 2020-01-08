<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Player extends Model
{
    protected $guarded = [];
    public $timestamps = true;

    public function teams() {
        return $this->belongsToMany(Team::class, 'season_player', 'player_id', 'team_id')->withPivot('season_id');
    }

    public function seasons() {
        return $this->belongsToMany(Player::class, 'season_player', 'player_id', 'season_id');
    }

    public function currTeam() {
        return $this->teams()->latest()->limit(1);
    }

    public function gamesStats() {
        return $this->hasMany(PlayerGameStats::class, 'player_id');
    }

    public function scopeGetStats($q, $field) {
      $q->whereHas('gamesStats', function($q) use($field) {
         $q->where($field, '>', 0);
       })->withCount(['gamesStats as goals' => function($q) use($field)  {
         $q->select(DB::raw("SUM(" .$field. ") as goals"));
       }])->orderBy($field, 'desc');
    }

    public static function saveOne(Request $request) {

        self::updateOrCreate(
          ['id' => $request->id],
          $request->only(['first_name', 'last_name', 'number'])
        );

        if ($request->hasFile('avatar')) {
          // add player avatar
        }
    }
}
