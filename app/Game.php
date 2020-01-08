<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public $timestamps = true;
    protected $guarded = [];

    public function homeTeam() {
      return $this->belongsTo(Team::class, 'team_id');
    }

    public function opponent() {
      return $this->belongsTo(Team::class, 'opponent_id');
    }

    public static function saveGame(Request $request) {
      $gameStats = [];
      $gameModel = self::updateOrCreate(
        ['id' => $request->id],
        $request->only([
        'team_id',
        'opponent_id',
        'team_goals',
        'opponent_goals'
        ])
      );

      collect($request->stats)->each(function($row) use (&$gameStats) {
        $key = $row['player_id'];
        unset($row['player_id']);

        $gameStats[$key] = $row;
      });

      $gameModel->players()->sync($gameStats);
    }


}
