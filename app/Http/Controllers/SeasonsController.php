<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Season;
use App\Http\Resources\Seasons\{SeasonResource, SeasonsCollection};
use Illuminate\Support\Facades\Validator;

class SeasonsController extends Controller
{
    public function save(Request $request) {
        $validation = Validator::make($request->all(), [
            'id' => 'exists:seasons',
            'name' => 'required'
        ]);
        $success = false;

        if (!$validation->fails()) {
            Season::updateOrCreate(['id' => $request->id], $request->only('name'));
            $success = true;
        }

        return $this->success($success);
    }

    public function getSeasonsRating(Request $request) {

    }

    public function getOne(Request $request) {
      $season = Season::with(['teams'])->find($request->id);

      return new SeasonResource($season);
    }

    public function getAll(Request $request) {
      $seasons = Season::withCount(['teams', 'players'])
      ->orderBy('teams_count', $request->order ?? 'desc')
      ->get();

      return new SeasonsCollection($seasons);
    }

    public function delete(Request $request) {
      $success = (boolean) Season::destroy($request->id);

      return $this->success($success);
    }

}
