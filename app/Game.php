<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Game extends Model
{
    public $timestamps = true;
    protected $guarded = [];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function opponent()
    {
        return $this->belongsTo(Team::class, 'opponent_id');
    }

    public function images()
    {
        return $this->morphToMany(Image::class, 'imageable');
    }

    public function favorites() {
        return $this->morphMany(Favorite::class, 'favoriable');
    }

    public static function saveGame($data, $files)
    {
        $gameStats = [];
        $gameModel = self::updateOrCreate(
            ['id' => $data['id']],
            collect($data)->pluck([
                'team_id',
                'opponent_id',
                'team_goals',
                'opponent_goals'
            ])
        );

        collect($data['stats'])->each(function ($row) use (&$gameStats) {
            $key = $row['player_id'];
            unset($row['player_id']);

            $gameStats[$key] = $row;
        });

        $gameModel->players()->sync($gameStats);
        Image::assign(Game::class, $data['id'], $files);

    }


}
