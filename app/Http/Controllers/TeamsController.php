<?php

namespace App\Http\Controllers;
use App\{Team, Player};
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Resources\Teams\{TeamResource, TeamsCollection};
use App\Http\Resources\Players\{PlayersCollection};

class TeamsController extends Controller
{

    public function save(Request $request) {
      $validation = Validator::make($request->all(), [
          'id' => 'exists:teams',
          'name' => 'required|min:3'
      ]);
      $success = false;

      if (!$validation->fails()) {
        Team::saveOne($request);
        $success = true;
      }

      return $this->success($success);
    }

    public function getOne(Request $request) {
      $team = Team::with(['seasons', 'players', 'currSeason'])->find($request->id);

      return new TeamResource($team);
    }

    public function getTeamsBySeason(Request $request) {
      $teams = Team::whereHas('seasons', function($q) use ($request) {
        $q->where('season_id' , $request->id);
      })->get();

      return new TeamsCollection($teams);
    }

    public function getTeamPlayers(Request $request) {
      $players = Player::whereHas('currTeam', function($q) use ($request) {
        $q->where('team_id', $request->id);
      })->get();

      return new PlayersCollection($players);
    }

    public function savePlayersInSeason(Request $request) {
        $validation = Validator::make($request->all(), [
            'team_id' => 'required|exists:teams,id',
            'season_id' => 'required|exists:seasons,id',
            'players.*' => 'required|exists:players,id'
        ]);
        $success = false;

        if (!$validation->fails()) {
            Team::attachPlayerInSeason($request);
            $success = true;
        }

        return $this->success($success);
    }

    public function delete(Request $request) {
      $success = (boolean) Team::destroy($request->id);

      return $this->success($success);
    }
}
