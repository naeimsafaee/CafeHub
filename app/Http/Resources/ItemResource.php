<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use TCG\Voyager\Facades\Voyager;

class ItemResource extends JsonResource{

    public function toArray($request){
        return [
            "id" => $this->id,
            "name" => $this->name,
            "price" => ((int) $this->price),
            "rate" => $this->rate,
            "cart_count" => $this->cart_count() ? $this->cart_count()->count : 0,
            "image" => Voyager::image($this->image),
            "category_id" => $this->category_id,
//            "category" => new CategoryResource($this->category),
        ];
    }
}
