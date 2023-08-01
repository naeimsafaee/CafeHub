<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientItemCollection;
use App\Http\Resources\ItemCollection;
use App\Http\Resources\ItemResource;
use App\Http\Resources\TransactionResource;
use App\Models\Cart;
use App\Models\Client;
use App\Models\ClientItem;
use App\Models\Item;
use App\Models\Telegram;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use TCG\Voyager\Models\Transaction;
use Zarinpal\Zarinpal;
use GuzzleHttp\Exception\RequestException;

class CartController extends Controller{

    public function index(){

        $items = Item::query()->whereHas('cart', function(Builder $query){
            $query->where('client_id', auth()->guard('api')->user()->id)->orderByDesc("count");
        })->get();

        return _response((new ItemCollection($items)));
    }

    public function store(Request $request){
        //
    }

    public function last_cart(){

        $client_items = ClientItem::query()->where('client_id', auth()->guard('api')->user()->id)->orderByDesc("updated_at")->with('item')->get()->groupBy('transaction_id')->values();

        $return = [];

        $index = 0;
        foreach($client_items as $client_item){
            foreach($client_item as $c_i){
                $return[$index]["created_at"] = $c_i->created_at;
                $return[$index]["transaction_id"] = $c_i->transaction_id;
                $return[$index]["items"][] = new ItemResource($c_i->item);
            }
            $index++;
        }

        return _response($return, "ok");
    }

    public function order_again(Request $request){

        $items = Item::query()->whereHas('bought', function(Builder $query) use ($request){
            $query->where('transaction_id', $request->transaction_id);
        })->get();

        foreach($items as $item){
            Cart::query()->create([
                "count" => $item->bought->where('client_id', auth()->guard('api')->user()->id)->first()->count,
                "item_id" => $item->id,
                "client_id" => auth()->guard('api')->user()->id,
                "ip" => \request()->ip(),
            ]);
        }

        return _response("", "ok");
    }

    public function show($id){
        $item = Item::query()->findOrFail($id);

        Cart::query()->updateOrCreate([
            'item_id' => $item->id,
            "client_id" => auth()->guard('api')->user()->id,
        ], [
            "count" => DB::raw('count+1'),
            "item_id" => $item->id,
            "client_id" => auth()->guard('api')->user()->id,
            "ip" => \request()->ip(),
        ]);

        return _response("", "ok");
    }

    public function update(Request $request, $id){
        //
    }

    public function destroy($id){
        $item = Item::query()->findOrFail($id);

        $cart = Cart::query()->updateOrCreate([
            'item_id' => $item->id,
            "client_id" => auth()->guard('api')->user()->id,
        ], [
            "count" => DB::raw('count-1'),
            "item_id" => $item->id,
            "client_id" => auth()->guard('api')->user()->id,
            "ip" => \request()->ip(),
        ]);

        $temp_cart = Cart::query()->where([
            'item_id' => $item->id,
            "client_id" => auth()->guard('api')->user()->id,
        ])->first();
        if($temp_cart && $temp_cart->count === 0)
            $temp_cart->delete();

        return _response("", "ok");
    }

    public function pay(Request $request){

        $client_id = auth()->guard('api')->user()->id;
        $client = Client::query()->findOrFail($client_id);
        $carts = Cart::query()->where('client_id', $client_id)->get();
        if($carts->count() == 0)
            return _response("", "cart is empty");

        if($request->type == 0){

            foreach($carts as $cart){
                ClientItem::query()->create([
                    'item_id' => $cart->item_id,
                    'client_id' => $cart->client_id,
                    'count' => $cart->count,
                    'status' => false,
                    'type' => $cart->type,
                    'address' => $cart->address,
                ]);

                $cart->delete();
            }

            $client_items = ClientItem::query()->where('client_id', $client_id)->get()->groupBy('transaction_id')->values();

            $return = [];

            $index = 0;
            foreach($client_items as $client_item){
                foreach($client_item as $c_i){
                    $return[$index]["transaction_id"] = $c_i->transaction_id;
                    $return[$index]["items"][] = new ItemResource($c_i->item);
                }
                $index++;
            }

            return _response($return, "ok");
        }
        elseif($request->type == 1) {

            $price = $carts->sum('item.price');

            $response = Http::post('https://panel.aqayepardakht.ir/api/create', [
                'pin' => config('constants.PAY_API'),
                'amount' => $price,
                'callback' => route('cart_verify'),
            ]);

            $track_id = $response->body();

            if($response->status() == 200 && !is_numeric($track_id)){

                $transaction = \App\Models\Transaction::query()->create([
                    "bank_transaction_id" => $track_id,
                    "amount" => $price,
                    "wallet_amount" => 0,
                    "paid" => false,
                    "client_id" => $client_id,
                ]);

                return _response($transaction, "https://panel.aqayepardakht.ir/startpay/{$track_id}");
            } else {
                abort(500);
            }

        }
        else {

            $return_wallet = 0;

            $price = calculate_cart($carts , $client , $return_wallet  ,true);

            if($price >= 1000){

                $response = Http::post('https://panel.aqayepardakht.ir/api/create', [
                    'pin' => config('constants.PAY_API'),
                    'amount' => $price,
                    'callback' => route('cart_verify'),
                ]);

                $track_id = $response->body();

                if($response->status() == 200 && !is_numeric($track_id)){

                    $transaction = \App\Models\Transaction::query()->create([
                        "bank_transaction_id" => $track_id,
                        "amount" => $price,
                        "wallet_amount" => $return_wallet,
                        "paid" => false,
                        "client_id" => $client_id,
                    ]);

                    return _response(new TransactionResource($transaction), "https://panel.aqayepardakht.ir/startpay/{$track_id}");
                } else {
                    abort(500);
                }

            } else {

                $transaction = \App\Models\Transaction::query()->create([
                    "bank_transaction_id" => "0000",
                    "amount" => $price,
                    "wallet_amount" => $return_wallet,
                    "paid" => true,
                    "client_id" => $client_id,
                    "use_wallet" => true,
                ]);

                foreach($carts as $cart){
                    ClientItem::query()->create([
                        'item_id' => $cart->item_id,
                        'client_id' => $cart->client_id,
                        'count' => $cart->count,
                        'status' => false,
                        'type' => $cart->type,
                        'address' => $cart->address,
                        'transaction_id' => $transaction->id,
                    ]);

                    $cart->delete();
                }

                $client_items = ClientItem::query()->where('client_id', $client_id)->get()->groupBy('transaction_id')->values();

                $return = [];

                $index = 0;
                foreach($client_items as $client_item){
                    foreach($client_item as $c_i){
                        $return[$index]["transaction_id"] = $c_i->transaction_id;
                        $return[$index]["items"][] = new ItemResource($c_i->item);
                    }
                    $index++;
                }

                return _response($return, "ok");
            }

        }

    }

    public function change_type_of_pay(Request $request){

        $client_id = auth()->guard('api')->user()->id;

        $carts = Cart::query()->where('client_id', $client_id)->get();
        foreach($carts as $cart){
            $cart->type = $request->type;
            $cart->address = $request->address;
            $cart->save();
        }

        return _response("", "ok");
    }

    public function verify(Request $request){

        if(!$request->has('transid')){
            return abort(404);
        }

        $track_id = $request->transid;

        $transaction = Transaction::query()->where('bank_transaction_id', $track_id)->firstOrFail();
        $client_id = $transaction->client_id;

        $carts = Cart::query()->where('client_id', $client_id)->get();

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

            $transaction->paid = true;
            $transaction->save();

            $telegram = Telegram::query()->where('is_active', true)->get();

            foreach($telegram as $tlg){
                Http::post(config('constants.TELEGRAM') . "sendMessage", [
                    //            "chat_id" => '95634965',
                    'text' => ("یک سفارش جدید ثبت شده است." . PHP_EOL . "برای مشاهده به لینک زیر مراجعه فرمایید" . PHP_EOL . config('constants.APP_URL') . "/admin/client-items"),
                    "chat_id" => $tlg->chat_id,
                ]);
            }

            $orig_carts = $carts;

            foreach($carts as $cart){
                ClientItem::query()->create([
                    'item_id' => $cart->item_id,
                    'client_id' => $cart->client_id,
                    'count' => $cart->count,
                    'transaction_id' => $transaction->id,
                    'status' => false,
                ]);

                $cart->delete();
            }

            return view('cart.paid', compact('orig_carts', 'auth'));
        } else {
            return view('cart.un_paid');
        }
    }

}
