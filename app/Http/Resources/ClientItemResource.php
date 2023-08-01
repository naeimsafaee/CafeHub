<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientItemResource extends JsonResource{

    public function toArray($request){
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'transaction_id' => $this->transaction_id,
            'item' => new ItemResource($this->item),
        ];
    }
}
