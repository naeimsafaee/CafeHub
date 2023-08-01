<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservCafeRequest;
use App\Models\CafeReserv;
use App\Models\Cart;
use App\Models\Item;
use App\Models\ReservEvent;
use App\Rules\ReCaptchaRule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReserveController extends Controller {

    public function reserve_cafe(Request $request) {
        Validator::make($request->all(), [
            'name' => ['required'],
            'phone' => ['required'],
            'count' => ['required'],
            'description' => ['required'],
            'g-recaptcha-response' => ['required', 'captcha']
//            'recaptcha_token' => ['required', new ReCaptchaRule($request->recaptcha_token)],
        ], [
            'name.required' => "لطفا نام خود را وارد کنید ",
            'phone.required' => "لطفا موبایل خود را وارد کنید ",
            'count.required' => "لطفا تعداد  را وارد کنید ",
            'description.required' => "لطفا توضیحات خود را وارد کنید ",
        ])->validate();

        $cafe = CafeReserv::query()->create([
            'name' => $request->name,
            'phone' => $request->phone,
            'count' => $request->count,
            'description' => $request->description,
        ]);

        if (auth()->guard('clients')->check())
            $cafe->client_id = auth()->guard('clients')->user()->id;

        $cafe->save();

        send_telegram_messages("درخواست رزرو🖋" . PHP_EOL . '〰️〰️〰️〰️〰️〰️〰️' . PHP_EOL . '👤' . $request->name . PHP_EOL . '👥' . fa_number($request->count) . " نفر" . PHP_EOL . '📞' . $request->phone . PHP_EOL . '〰️〰️〰️〰️〰️〰️〰️' . PHP_EOL . '📝' . $request->description);

        return redirect()->route('show_success_reserve');
    }

    public function show_success_reserve() {

        return view('cart.paid_reserve');
    }

}
