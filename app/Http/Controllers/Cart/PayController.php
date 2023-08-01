<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Client;
use App\Models\ClientItem;
use App\Models\Item;
use App\Models\Telegram;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use TCG\Voyager\Models\Transaction;
use Zarinpal\Zarinpal;

class PayController extends Controller {

    public function pay(Request $request) {

        $client_id = auth()->guard('clients')->user()->id;
        $client = Client::query()->findOrFail($client_id);
        $client_wallet = $client->wallet;

        $carts = Cart::query()->where('client_id', $client_id)->get();

        $return_wallet = 0;

        if ($request->has('use_wallet')) {
            $price = calculate_cart($carts, $client, $return_wallet);

            $paid_from_wallet_amount = $client_wallet - $return_wallet;

            $auth = rand(1000, 1000000000000);

            $transaction = \App\Models\Transaction::query()->create([
                "bank_transaction_id" => $auth,
                "amount" => $price,
                "wallet_amount" => $paid_from_wallet_amount,
                "paid" => false,
                "client_id" => $client_id,
            ]);

            foreach($carts as $cart) {

                $cart->left_amount = $price;
                $cart->auth = $auth;
                $cart->save();

                ClientItem::query()->create([
                    'item_id' => $cart->item_id,
                    'transaction_id' => $transaction->id,
                    'client_id' => $cart->client_id,
                    'count' => $cart->count,
                    'status' => false,
                ]);
            }

            return redirect()->route('success', ["auth" => $auth]);
        } else {
            $price = $carts->sum('amount');

            if ($price <= 1000) {

                $auth = rand(1000, 1000000000000);

                $transaction = \App\Models\Transaction::query()->create([
                    "bank_transaction_id" => $auth,
                    "amount" => $price,
                    "wallet_amount" => 0,
                    "paid" => true,
                    "client_id" => $client_id,
                ]);

                foreach($carts as $cart) {

                    $cart->left_amount = 0;
                    $cart->auth = $auth;
                    $cart->save();

                    ClientItem::query()->create([
                        'item_id' => $cart->item_id,
                        'transaction_id' => $transaction->id,
                        'client_id' => $cart->client_id,
                        'count' => $cart->count,
                        'status' => false,
                    ]);
                }

//            send_telegram_messages(("یک سفارش جدید ثبت شده است." . PHP_EOL . "برای مشاهده به لینک زیر مراجعه فرمایید" . PHP_EOL . config('constants.APP_URL') . "/admin/client-items"));

                return redirect()->route('success', ["auth" => $auth]);
            }

            $response = Http::post('https://panel.aqayepardakht.ir/api/create', [
                'pin' => config('constants.PAY_API'),
                'amount' => $price,
                'callback' => route('cart_verify'),
            ]);

            $track_id = $response->body();

            foreach($carts as $cart) {

                $cart->left_amount = $price;
                $cart->auth = $track_id;
                $cart->save();
            }


            if ($response->status() == 200 && !is_numeric($track_id)) {

                if ($carts->first()->type == 0 && $carts->first()->address == null) {

                    \App\Models\Transaction::query()->create([
                        "bank_transaction_id" => $track_id,
                        "amount" => 0,
                        "wallet_amount" => 0,
                        "paid" => false,
                        "client_id" => $client_id,
                    ]);

                } else {
                    \App\Models\Transaction::query()->create([
                        "bank_transaction_id" => $track_id,
                        "amount" => $price,
                        "wallet_amount" => 0,
                        "paid" => false,
                        "client_id" => $client_id,
                    ]);

                }

                return redirect("https://panel.aqayepardakht.ir/startpay/{$track_id}");
            } else {
                abort(500);
            }
        }


        //$carts->first()->type == 0 && $carts->first()->address == null

    }

    public function pay_type(Request $request) {

        $client = auth()->guard('clients')->user();
        $carts = Cart::query()->where('client_id', $client->id)->get();

        if ($request->type == 1) {
            foreach($carts as $cart) {
                $cart->type = 0;
                $cart->save();
            }
        }

        $wallet_price = 0;

        $price = calculate_cart($carts, $client, $wallet_price, false);

        $all_price = $carts->sum('amount');

        return view('cart.pay_type', compact('price', 'all_price', 'wallet_price'));
    }

    public function verify(Request $request) {

        if (!$request->has('transid')) {
            return abort(404);
        }

        $track_id = $request->transid;

        $transaction = Transaction::query()->where('bank_transaction_id', $track_id)->firstOrFail();
        $client_id = $transaction->client_id;

        $orig_carts = Cart::query()->where('client_id', $client_id)->get();

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

        if ($response->getStatusCode() == 200 && $status === "1") {

            $transaction->paid = true;
            $transaction->save();

            $client = Client::query()->find($client_id);

            $text = "💳" . "خرید جدید " . PHP_EOL .
                '〰️〰️〰️〰️〰️〰️〰️' . PHP_EOL .
                '👤' . $client->name . PHP_EOL .
                (($orig_carts->first()->type === 0) ? "تحویل حضوری📍" :
                    ("📍" . "ارسال به 📍" . $orig_carts->first()->address)) . PHP_EOL
                . '〰️〰️〰️〰️〰️〰️〰️' . PHP_EOL;

            foreach($orig_carts as $cart) {

                $text .= '▪️' . $cart->item->name . " × " . $cart->count . PHP_EOL;

                ClientItem::query()->create([
                    'item_id' => $cart->item_id,
                    'client_id' => $cart->client_id,
                    'count' => $cart->count,
                    'transaction_id' => $transaction->id,
                    'status' => false,
                ]);

                $cart->auth = $track_id;
                $cart->save();
            }

            $text .= '〰️〰️〰️〰️〰️〰️〰️' . PHP_EOL . '💰' . 'قابل پرداخت :‌ ' . $transaction->amount . PHP_EOL;
            if(session()->has('table')){
                $table = session('table');
                $text .= "شماره میز: " . $table . PHP_EOL;
            }

            send_telegram_messages($text);

            $auth = $track_id;

            return view('cart.paid', compact('orig_carts', 'auth'));
        } else {
            return view('cart.un_paid');
        }

    }

}
