<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Game};
use App\Http\Resources\Games\{GameResource, GamesCollection};
use Illuminate\Support\Facades\Validator;

class GamesController extends Controller
{
    public function save(Request $request) {

        var_dump(json_decode($request->data, true));
        exit;

        $validation = Validator::make(json_decode($request->data, true), [
            'team_id' => 'required|exists:teams,id',
            'opponent_id' => 'required|exists:teams,id',
            'team_goalds' => 'required|numeric',
            'opponent_goals' => 'required|numeric',
            'stats' => 'required|array',
            'stats.*.player_id' => 'required|exists:players,id',
            'stats.*.team_id' => 'required|exists:teams,id',
            'stats.*.goals' => 'required|numeric',
            'stats.*.goal_passes' => 'required|mumeric',
            'stats.*.blocks' => 'required|numeric',
            'stats.*.catches' => 'required|numeric',
        ]);
        $success = false;

        if (!$validation->fails()) {
            $success = false;

            Game::saveGame(json_decode($request->data, true), $request->files);
        }

        return $this->success($success);
    }

    public function getList(Request $request) {
      $games = Game::with([
          'homeTeam',
          'opponent',
          'players',
          'players.currTeam,id'
        ])->paginate(15, '*', null, $request->page ?? 1);

      return new GamesCollection($games);
    }

    public function getOne(Request $request) {
        $game = Game::with(['homeTeam', 'opponent', 'players'])
        ->find($request->id);

        return new GameResource($game);
    }

    public function delete(Request $request) {
      $success = (boolean) Game::destroy($request->id);

      return $this->success($success);
    }
}
