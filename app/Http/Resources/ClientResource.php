<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use TCG\Voyager\Facades\Voyager;

class ClientResource extends JsonResource{

    public function toArray($request){
        return [
            'avatar' => $this->avatar ? Voyager::image($this->avatar) : asset('assets/photo/avatar.png'),
            "name" => $this->name,
            "phone" => $this->phone,
            "wallet" => $this->wallet,
        ];
    }
}
