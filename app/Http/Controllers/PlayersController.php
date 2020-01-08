<?php

namespace App\Http\Controllers;

use App\{Season, Player};
use App\Http\Resources\Players\{PlayerResource, PlayersCollection};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayersController extends Controller
{
    public function save(Request $request)
    {
      $validation = Validator::make($request->all(), [
          'id' => 'exists:players',
          'first_name' => 'required|min:3',
          'last_name' => 'required|min:3',
          'number' => 'required|numeric|min:1|max:99'
      ]);
      $success = false;

      if (!$validation->fails()) {
         Player::saveOne($request);
         $success = true;
      }

      return $this->success($success);
    }

    public function getOne(Request $request) {
      $player = Player::with(['seasons', 'currTeam'])->find($request->id);

      return new PlayerResource($player);
    }

    public function getPlayersBySeason(Request $request) {
        $players = Season::find($request->season_id)
        ->players()
        ->get();

        return new PlayersCollection($players);
    }

    public function delete(Request $request) {
      $success = (boolean)Player::destroy($request->id);

      return $this->success($success);
    }




}
