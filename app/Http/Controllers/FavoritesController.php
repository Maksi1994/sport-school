<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Http\Resources\Favorites\FavoritesCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoritesController extends Controller
{

    public function toggle(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'type' => 'in:game,player,team',
            'type_id' => 'required|numeric'
        ]);
        $success = false;

        if (!$validation->fails()) {
            $success = Favorite::toggle($request);
        }

        return $this->success($success);
    }

    public function getAll(Request $request)
    {
        $favorites  = $request->user()->favorites()->with('favoriable')->get();

        return new FavoritesCollection($favorites);
    }

}
