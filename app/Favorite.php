<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Favorite extends Model
{

    public $timestamps = true;
    public $guarded = [];

    public function favoriable() {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function toggle(Request $request)
    {
        $modelName = 'App\\'.ucfirst($request->type);
        $model = $modelName::find($request->type_id);
        $success = false;

        if (!empty($model)) {
            $includedInFavorites = $model->favorites()->where('user_id', $request->id)->first();

            if (!empty($includedInFavorites)) {
                $includedInFavorites->delete();
            } else {
                $model->favorites()->create([
                    'user_id' => $request->user()->id
                ]);
            }

            $success = true;
        }

        return $success;
    }
}
