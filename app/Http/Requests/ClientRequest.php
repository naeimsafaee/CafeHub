<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest{

    public function rules(){
        return [
            'avatar' => ['image', 'mimes:jpg,png,jpeg' , 'required'],
        ];
    }
}
