<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{

    public $timestamps = true;
    protected $guarded = [];

    const MIME_TYPES = [
        'image/jpeg',
        'image/png'
    ];

    public function games() {
        return $this->morphedByMany(Game::class, 'imageable');
    }

    public function teams() {
        return $this->morphedByMany(Team::class, 'imageable');
    }

    public static function assign($modelSpace, $typeId, $images) {
        $model =  $modelSpace::find($typeId);
        $imagesIds = [];

        if (!empty($model)) {
            foreach ($images as $img) {
                if (is_array($img) && !empty($img['file'])) {
                    $path = Storage::disk('s3')->putFile('images', $img['file']);

                    $imagesIds[] = self::create([
                        'src' => $path,
                        'desc' => 'AAA',
                    ])->id;
                } else {
                    $imagesIds[] = $img;
                }

            }

            $model->images()->attach($imagesIds);
        }

       // var_dump($modelName);
    }

}
