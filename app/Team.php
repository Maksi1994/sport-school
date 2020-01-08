<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
  protected $guarded = [];
  public $timestamps = true;

  public function seasons() {
    return $this->belongsToMany(Season::class, 'season_player', 'team_id', 'season_id')->withPivot('season_id');
  }

  public function players() {
      return $this->belongsToMany(Player::class, 'season_player', 'team_id', 'player_id')->withPivot('season_id');
  }

  public function currSeason() {
      return $this->seasons()->latest()->limit(1);
  }

  public static function saveOne(Request $request) {
    self::updateOrCreate([
      'id' => $request->id
    ], $request->only([
      'name'
    ]));
  }

  public static function attachPlayerInSeason(Request $request) {
    $playerAttachableData = [];

    collect($request->players)->each(function($playerId) use ($request, &$playerAttachableData) {
      $playerAttachableData[$playerId] = [
        'team_id' => $request->team_id,
        'season_id' => $request->season_id
      ];
    });

    self::find($request->team_id)
    ->players()
    ->wherePivot('season_id', $request->season_id)
    ->sync($playerAttachableData);
  }

}
