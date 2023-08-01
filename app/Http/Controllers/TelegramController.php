<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Telegram;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Monolog\Handler\TelegramBotHandler;
use TCG\Voyager\Models\Setting;

class TelegramController extends Controller {

    public function get(Request $request) {

        $array = $request->all();

        /*Telegram::query()->create([
            'chat_id' => json_encode($array["message"]),
            'is_active' => false
        ]);*/

        if ($array["message"]["text"] === "/start" || $array["message"]["text"] === "\/start") {
            $response = Http::post(config('constants.TELEGRAM') . "sendMessage", [
                //            "chat_id" => '95634965',
                'text' => "به بات کافه هاب خوش آمدید.",
                "chat_id" => $array["message"]["chat"]["id"],
            ]);
        } elseif ($array["message"]["text"] === "/connect" || $array["message"]["text"] === "\/connect") {

            Telegram::query()->create([
                'chat_id' => $array["message"]["chat"]["id"],
                'is_active' => false,
            ]);

            $response = Http::post(config('constants.TELEGRAM') . "sendMessage", [
                //            "chat_id" => '95634965',
                'text' => ("لطفا کد زیر را به مسئول سایت تحویل دهید." . PHP_EOL . $array["message"]["chat"]["id"]),
                "chat_id" => $array["message"]["chat"]["id"],
            ]);

        } elseif ($array["message"]["text"] === "/enable_send" || $array["message"]["text"] === "\/enable_send") {

            $setting = Setting::query()->where('key', 'site.send_cafe')->first();
            $setting->value = 1;
            $setting->save();

            $response = Http::post(config('constants.TELEGRAM') . "sendMessage", [
                'text' => 'ارسال فعال شد!',
                "chat_id" => $array["message"]["chat"]["id"],
            ]);

        } elseif ($array["message"]["text"] === "/disable_send" || $array["message"]["text"] === "\/disable_send") {

            $setting = Setting::query()->where('key', 'site.send_cafe')->first();
            $setting->value = 0;
            $setting->save();

            $response = Http::post(config('constants.TELEGRAM') . "sendMessage", [
                'text' => 'ارسال غیر فعال شد!',
                "chat_id" => $array["message"]["chat"]["id"],
            ]);

        } elseif ($array["message"]["text"] === "/checkout" || $array["message"]["text"] === "\/checkout") {

            Telegram::query()->create(['chat_id' => $array["message"]["chat"]["id"], 'is_active' => false]);

            $amount = Transaction::query()->whereDate('created_at', Carbon::today())->sum('amount');
            $wallet_amount = Transaction::query()->whereDate('created_at', Carbon::today())->sum('wallet_amount');

            $response = Http::post(config('constants.TELEGRAM') . "sendMessage", [
                'text' => ('مبلغ کل تراکنش های امروز(پرداخت در کافه و درگاه) : ' . $amount . PHP_EOL
                    . "مبلغ خرید شده با کیف پول : " . $wallet_amount . PHP_EOL .
                'مجموع فروش (پرداختی + کیف پول): ' . (((float)$amount) + ((float)$wallet_amount)) . PHP_EOL
            ),
                "chat_id" => $array["message"]["chat"]["id"],
            ]);

        } elseif ($array["message"]["text"] === "/change_stock" || $array["message"]["text"] === "\/change_stock") {

            $response = Http::post(config('constants.TELEGRAM') . "sendMessage", [
                'text' => 'لیست محصولات',
                'reply_markup' => json_encode([
                    'keyboard' => $this->generate_items_keyboard()
                ]),
                "chat_id" => $array["message"]["chat"]["id"],
            ]);

        } else {

            $text = $array["message"]["text"];

            $text = str_replace(' 🔴 ' , '' , $text);
            $text = str_replace(' ' , '' , $text);

            $item = Item::query()->where('name' , $text)->first();

            if($item){

                if($item->is_available){
                    $item->is_available = false;
                    $item->save();

                    $response = Http::post(config('constants.TELEGRAM') . "sendMessage", [
                        'text' => $item->name . ' غیر موجود شد!',
                        'reply_markup' => json_encode([
                            'keyboard' => $this->generate_items_keyboard()
                        ]),
                        "chat_id" => $array["message"]["chat"]["id"],
                    ]);
                } else {
                    $item->is_available = true;
                    $item->save();

                    $response = Http::post(config('constants.TELEGRAM') . "sendMessage", [
                        'text' => $item->name . ' موجود شد!',
                        'reply_markup' => json_encode([
                            'keyboard' => $this->generate_items_keyboard()
                        ]),
                        "chat_id" => $array["message"]["chat"]["id"],
                    ]);
                }

            }

            return;
        }

        return;
    }

    public function set_webhook() {

        // config("constants.APP_URL") .
        $response = Http::get(config('constants.TELEGRAM') . "setWebhook?url=" . route('telegram'));

        return _response($response);
    }

    private function generate_items_keyboard() {

        $items = \App\Models\Item::all();

        $item_array = [];

        $index = 0;
        $main_index = 0;

        foreach($items as $item) {

            if($index % 3 === 0){
                $main_index++;
                $index = 0;
            }

            $item_array[$main_index][] = $item->name . ($item->is_available ? '' : ' 🔴 ');

            $index++;
        }

        $item_array = array_values($item_array);

        return $item_array;
    }

}
