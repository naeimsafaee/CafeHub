<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller{

    public function index(){
        return _response(new ClientResource(Client::query()->findOrFail(auth()->guard('api')->user()->id)));
    }

    public function update(ClientRequest $request, $id){

        $file = $request->avatar;
        $fileName = 'avatar/' . time() . '-' . rand() . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->put($fileName, file_get_contents($file));

        $client = auth()->guard('api')->user();
        $client->avatar = $fileName;
        $client->save();

        return _response(new ClientResource($client), "avatar has been set!");
    }

    public function charge($phone){
        Client::query()->where('phone' , $phone)->update([
            'wallet' => 20000
        ]);
        return _response("ok");
    }
}
