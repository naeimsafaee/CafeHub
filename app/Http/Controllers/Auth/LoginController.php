<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Client;
use App\Models\ReservEvent;
use Illuminate\Http\Request;
use App\Notifications\SendSMS;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller{

    public function __invoke(Request $request){
        session(['url.intended' => url()->previous()]);
        return view('auth.login');
    }

    public function register(Request $request){

        Validator::make($request->all(), [
            'phone' => ['required', 'size:11'],
        ], [
            'phone.required' => "لطفا شماره تلفن خود را وارد کنید ",
            'phone.size' => "لطفا شماره خود را به درستی وارد کنید",
        ])->validate();

        $code = rand(10000, 99999);

        $client = Client::query()->updateOrCreate([
            'phone' => $request->phone,
        ], [
            'phone' => $request->phone,
            'is_verify' => false,
            'code' => $code,
        ]);

        session(['phone' => $request->phone]);
        $client->notify(new SendSMS($client->phone, $code));

        return redirect()->route('verify');

    }

    public function verify(){
        return view('auth.verify');
    }

    public function code(Request $request){

        Validator::make($request->all(), [
            'code' => [
                'required',
                function($attribute, $value, $fail){

                    $client = Client::query()->where('phone', Session('phone'))->get();

                    if(!$client)
                        return $fail('کاربر وجود ندارد.');

                    if($value != $client->first()->code)
                        return $fail('کد صحیح نیست');

                },
            ],
        ], [
            'code.required' => "لطفا کد تایید  خود را وارد کنید ",

        ])->validate();


        $client = Client::query()->where('phone', Session('phone'))->first();
        $client->is_verify = true;
        $client->save();

        Auth::guard('clients')->loginUsingId($client->id , true);

//        $carts = Cart::query()->where('ip', \request()->ip())->get();
        $reserve_event = ReservEvent::query()->where('ip', \request()->ip())->get();

       /* foreach($carts as $cart){
            $cart->client_id = $client->id;
            $cart->save();
        }*/

        foreach($reserve_event as $event){
            $event->client_id = $client->id;
            $event->save();
        }
        if($client->name)
            return redirect(session()->get('url.intended'));
        else
            return redirect()->route('name');

    }

    public function name(){
        return view('auth.name');
    }

    public function name_submit(Request $request){
        Validator::make($request->all(), [
            'name' => ['required'],
        ], [
            'name.required' => "لطفا نام خود را وارد کنید ",
        ])->validate();

        $client = Client::query()->where('phone', Session('phone'))->first();
        $client->name = $request->name;
        $client->save();

        return redirect(session()->get('url.intended'));
    }

    public function logout(){
        \auth()->guard('clients')->logout();
        return redirect()->route('login');
    }

}
