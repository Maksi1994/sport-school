<?php

namespace App\Http\Resources\Favorites;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FavoritesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
