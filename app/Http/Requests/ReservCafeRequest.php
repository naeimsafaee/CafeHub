<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservCafeRequest extends FormRequest{

    public function rules(){
        return [
            'name' => ['string' , 'required'],
            'phone' => ['string' , 'required'],
            'count' => ['string' , 'required'],
            'description' => ['string'],
        ];
    }
}
