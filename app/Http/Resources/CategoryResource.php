<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use TCG\Voyager\Facades\Voyager;

class CategoryResource extends JsonResource{

    public function toArray($request){
        return [
            "id" => $this->id,
            "title" => $this->title,
            "image" => Voyager::image($this->image),
        ];
    }
}
