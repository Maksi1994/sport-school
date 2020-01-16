<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Player;
use App\Http\Resources\Stats\{PlayersOffensiveCollection};
use Illuminate\Support\Facades\Validator;

class StatsController extends Controller
{

    public function getPlayersStats(Request $request) {
      $validation = Validator::make(['field' => $request->field], [
        'field' => 'required|in:goals,goal_passes'
      ]);
      $field = $request->field;

      if (!$validation->fails()) {

        $players = Player::getStats($field)->paginate(10, '*', null, $request->page ?? 1);

        return new PlayersOffensiveCollection($players);
      }

      return $this->success(false);
    }




}
