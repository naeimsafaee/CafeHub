<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use TCG\Voyager\Facades\Voyager;

class EventResource extends JsonResource{

    public function toArray($request){
        return [
            "id" => $this->id,
            "title" => $this->title,
            "price" => (int) $this->price,
            "capacity" => $this->capacity,
            "description" => $this->description,
            "image" => Voyager::image($this->image),
            "time_at" => $this->time_at,
            "status" => $this->status,
        ];
    }
}
