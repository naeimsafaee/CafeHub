<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Client;
use App\Models\Item;
use App\Models\ReservEvent;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller {

    public function index() {

        $items = Item::query()->whereHas('cart', function(Builder $query) {
            $query->where('ip', \request()->ip());
        })->with('cart')->get();

        return view('pages.order', compact('items'));
    }

    public function address() {

        $client_id = auth()->guard('clients')->user()->id;
        $carts = Cart::query()->where('client_id', $client_id)->get();

        foreach($carts as $cart) {
            $cart->type = 2;
            $cart->save();
        }

        return view('cart.address');
    }

    public function address2(Request $request) {
        Validator::make($request->all(), [
            'address' => ['required'],
        ], [
            'address.required' => "لطفا آدرس را وارد کنید ",
        ])->validate();

        if (auth()->guard('clients')->check()) {
            $client_id = auth()->guard('clients')->user()->id;
            $carts = Cart::query()->where('client_id', $client_id)->get();
        } else
            $carts = Cart::query()->where('ip', \request()->ip())->get();

        foreach($carts as $cart) {
            $cart->type = 2;
            $cart->address = $request->address;
            $cart->save();
        }
        return redirect()->route('pay_type');
    }

    public function send(Request $request) {
        $client_id = auth()->guard('clients')->user()->id;

        $carts = Cart::query()->where('client_id', $client_id)->get();

        return view('cart.send', compact('carts'));
    }

    public function success() {

        $client_id = auth()->guard('clients')->user()->id;
        $orig_carts = Cart::query()->where('client_id', $client_id)
            ->withoutGlobalScope('auth_check')
            ->where('auth' , \request()->auth)->get();

        $client = Client::query()->find($client_id);


        $text = "💳" . "خرید جدید " . PHP_EOL .
            '〰️〰️〰️〰️〰️〰️〰️' . PHP_EOL .
            '👤' . $client->name . PHP_EOL .
            (($orig_carts->first()->type === 0) ? "تحویل حضوری📍" :
                ("📍" . "ارسال به 📍" . $orig_carts->first()->address)) . PHP_EOL
            . '〰️〰️〰️〰️〰️〰️〰️' . PHP_EOL;
        ;

        foreach($orig_carts as $cart) {

            $text .= '▪️' . $cart->item->name . " × " . $cart->count . PHP_EOL;

//            $text .= $cart->item->name . " : " . $cart->count . PHP_EOL;

//            $cart->delete();
        }

        $text .= '〰️〰️〰️〰️〰️〰️〰️' . PHP_EOL . '💰' . 'قابل پرداخت : ' . $orig_carts->first()->left_amount . ' تومان ' . PHP_EOL .
            '〰️〰️〰️〰️〰️〰️〰️' . PHP_EOL
            . '📱 شماره تلفن مشتری : ' . $client->phone . PHP_EOL;

        if(session()->has('table')){
            $table = session('table');
            $text .= "شماره میز: " . $table . PHP_EOL;
        }

        $auth = \request()->auth;

        $transaction = Transaction::query()->where('bank_transaction_id' , $auth)->firstOrFail();

        send_telegram_messages($text);

        return view('cart.paid', compact('orig_carts', 'auth' , 'transaction'));
    }

}
