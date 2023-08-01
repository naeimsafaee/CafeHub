<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource{

    public function toArray($request){
        return [
            "id" => $this->id,
            "bank_transaction_id" => $this->bank_transaction_id,
            "amount" => $this->amount,
            "paid" => $this->paid == 1,
            "created_at" => $this->created_at,
            "items" => $this->items,
            "event" => $this->event,
        ];
    }
}
