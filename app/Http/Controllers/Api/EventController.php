<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservEventRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\TransactionResource;
use App\Models\Cart;
use App\Models\Client;
use App\Models\ClientItem;
use App\Models\Event;
use App\Models\ReservEvent;
use App\Models\Telegram;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use TCG\Voyager\Models\Transaction;
use Zarinpal\Zarinpal;

class EventController extends Controller{

    public function index(){
        //
    }

    public function store(ReservEventRequest $request , Zarinpal $zarinpal){

        $event = Event::query()->findOrFail($request->event_id);

        if($event->capacity < $request->number){
            throw ValidationException::withMessages(['number' => 'event is full']);
        }

        /*$event->capacity -= $request->number;
        $event->save();*/

        $zarinpal->sandbox = true;

        $price = $event->price * $request->number;

        $payment = [
            'callback_url' => route('event_verify'), // Required
            'amount' => $price,                    // Required
            'description' => 'a short description',   // Required
            /*'metadata'     => [
                'mobile' => '0933xxx7694',       // Optional
                'email'  => 'saeedp47@gmail.com' // Optional
            ]*/
        ];

        try {
            $response = $zarinpal->request($payment);

            $code = $response['data']['code'];

            $message = $zarinpal->getCodeMessage($code);
            if($code === 100){
                $authority = $response['data']['authority'];

                $reserv = ReservEvent::query()->create([
                    'client_id' => auth()->guard('api')->user()->id,
                    'event_id' => $request->event_id,
                    'count' => $request->number
                ]);

                $client_id = auth()->guard('api')->user()->id;
                $client = Client::query()->findOrFail($client_id);

                $transaction = \App\Models\Transaction::query()->create([
                    "bank_transaction_id" => $authority,
                    "amount" => $price,
                    "paid" => false,
                    "product_id" => $reserv->id,
                    "client_id" => $client_id
                ]);

                return _response(new TransactionResource($transaction) , $zarinpal->getRedirectUrl($authority));
            }
            return "Error, Code: ${code}, Message: ${message}";
        } catch(RequestException $exception){
            return "Error, Code: " . $exception->getMessage();
        }
    }

    public function show($id){

        $event = Event::query()->findOrFail($id);

        return _response(new EventResource($event));
    }

    public function update(Request $request, $id){
        //
    }

    public function destroy($id){
        //
    }

    public function verify(Request $request){

        if(!$request->has('transid')){
            return abort(404);
        }

        $track_id = $request->transid;


        $transaction = Transaction::query()->where('bank_transaction_id', $track_id)->firstOrFail();

        $reservs = ReservEvent::query()->where('transaction_id' , $transaction->id)->firstOrFail();

        $data = [
            'pin' => config('constants.PAY_API'),
            'amount' => $transaction->amount,
            'transid' => $track_id,
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->post("https://panel.aqayepardakht.ir/api/verify", [
            'http_errors' => false,
            'headers' => [
                'content-type' => 'application/json',
            ],
            'body' => json_encode($data),
        ]);
        $status = $response->getBody()->getContents();

        if($response->getStatusCode() == 200 && $status === "1"){

            send_telegram_messages(
                "رزرو ایونت" . PHP_EOL . "تعداد : " . $reservs->count . PHP_EOL . $reservs->event->title
            );

            $transaction->paid = true;
            $transaction->save();

            $reservs->paid = true;
            $reservs->save();

            return view('cart.paid_event' , compact('auth'));
        } else {
            return view('cart.un_paid');
        }

    }


}
