<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservEventRequest extends FormRequest{

    public function rules(){
        return [
            'number' => ['string' , 'required'],
            'event_id' => ['string' , 'required' , 'exists:events,id'],
        ];
    }
}
