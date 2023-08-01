<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ReservCafeRequest;
use App\Models\CafeReserv;
use App\Models\Client;
use App\Notifications\SendSMS;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller{

    public function login(){

    }

    public function register(RegisterRequest $request){

        $code = rand(10000, 99999);

        $client = Client::query()->updateOrCreate([
            'phone' => $request->phone,
        ], [
            'phone' => $request->phone,
            'is_verify' => false,
            'code' => $code,
        ]);

        $client->notify(new SendSMS($client->phone, $code));

        return _response("", "sms has been sent!");
    }

    public function set_name(Request $request){
        $client = auth()->guard('api')->user();
        $client->name = $request->name;
        $client->save();

        return _response("", "name has been set!");
    }

    public function verify(Request $request){
        $client = Client::query()->where('code', $request->code)->firstOrFail();
        if($client->phone != $request->phone)
            return _response("phone number is wrong!", "", false);
        $client->code = null;
        $client->is_verify = true;
        $client->save();

        $token = $client->createToken('TokenForNaeim')->accessToken;

        return _response($token);
    }

    public function reserv_cafe(ReservCafeRequest $request){

        CafeReserv::query()->create([
            'client_id' => auth()->guard('api')->user()->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'count' => $request->count,
            'description' => $request->description,
        ]);

        send_telegram_messages(
            "درخواست رزرو🖋" . PHP_EOL . '〰️〰️〰️〰️〰️〰️〰️' . PHP_EOL .'👤' . $request->name . PHP_EOL . '👥' . fa_number($request->count) . " نفر" . PHP_EOL . '📞' .$request->phone . PHP_EOL .  '〰️〰️〰️〰️〰️〰️〰️' . PHP_EOL . '📝' . $request->description
        );

        return _response("", "ok");
    }

}
