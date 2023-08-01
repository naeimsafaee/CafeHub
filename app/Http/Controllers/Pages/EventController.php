<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Cart;
use App\Models\Client;
use App\Models\Event;
use App\Models\Item;
use App\Models\ReservEvent;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Zarinpal\Zarinpal;

class EventController extends Controller
{
    public function __invoke(Request $request)
    {

    }

    public function add_to_cart($id) {

        $item = Event::query()->findOrFail($id);

        if (auth()->guard('clients')->check()){
            ReservEvent::query()->updateOrCreate([
                'event_id' => $item->id,
                "client_id" => auth()->guard('clients')->user()->id,
            ], [
                "count" => DB::raw('count+1'),
                "event_id" => $item->id,
                "client_id" => auth()->guard('clients')->user()->id,
            ]);

            Cart::query()->where(
                "client_id" , auth()->guard('clients')->user()->id
            )->delete();

        } else {
            ReservEvent::query()->updateOrCreate([
                'event_id' => $item->id,
                "ip" => \request()->ip(),
            ], [
                "count" => DB::raw('count+1'),
                "event_id" => $item->id,
                "ip" => \request()->ip(),
            ]);

            Cart::query()->where(
                "ip" , \request()->ip()
            )->delete();
        }

        Session::put('reserve_event' , true);

        return redirect()->route('home');
    }

    public function destroy($id){
        $item = Event::query()->findOrFail($id);

        if (auth()->guard('clients')->check()){
            ReservEvent::query()->updateOrCreate([
                'event_id' => $item->id,
                "client_id" => auth()->guard('clients')->user()->id,
            ], [
                "count" => DB::raw('count-1'),
                "event_id" => $item->id,
                "client_id" => auth()->guard('clients')->user()->id,
            ]);
            $temp_cart = ReservEvent::query()->where([
                'event_id' => $item->id,
                "client_id" => auth()->guard('clients')->user()->id,
            ])->first();
        }
        else {
            ReservEvent::query()->updateOrCreate([
                'event_id' => $item->id,
                "ip" => \request()->ip(),
            ], [
                "count" => DB::raw('count-1'),
                "event_id" => $item->id,
                "ip" => \request()->ip(),
            ]);
            $temp_cart = ReservEvent::query()->where([
                'event_id' => $item->id,
                "ip" => \request()->ip(),
            ])->first();
        }

        if($temp_cart && $temp_cart->count === 0)
            $temp_cart->delete();

        return redirect()->route('home');
    }

    public function pay_reserve(Request $request , Zarinpal $zarinpal) {

        $client_id = auth()->guard('clients')->user()->id;
        $reserve = ReservEvent::query()->where('client_id', $client_id)->first();

        $price = 0;

        $event = Event::query()->findOrFail($reserve->event_id);
        $event->capacity -= $reserve->count;
        $price += $reserve->count * $event->price;

        $payment = [
            'callback_url' => route('event_verify'), // Required
            'amount' => $price * 10,                    // Required
            'description' => 'a short description',   // Required
        ];

        try {
            $response = $zarinpal->request($payment);

            $code = $response['data']['code'];

            $message = $zarinpal->getCodeMessage($code);
            if($code === 100){
                $authority = $response['data']['authority'];

                $transaction = \App\Models\Transaction::query()->create([
                    "bank_transaction_id" => $authority,
                    "amount" => $price,
                    "paid" => false,
                    "product_id" => $reserve->id,
                    "client_id" => $client_id
                ]);

                $reserve->transaction_id = $transaction->id;
                $reserve->save();

                return redirect($zarinpal->getRedirectUrl($authority));
            }
            return "Error, Code: ${code}, Message: ${message}";
        } catch(RequestException $exception){
            return "Error, Code: " . $exception->getMessage();
        }
    }

}
