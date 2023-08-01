<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest{

    public function rules(){
        return [
            "phone" => [ 'string'],
            "code" => ["exists:clients,code", 'string'],
            "name" => ['string'],
        ];
    }

}
