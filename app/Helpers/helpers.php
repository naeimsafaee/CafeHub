<?php

use App\Models\Client;
use App\Models\Telegram;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use TCG\Voyager\Models\Meta;
use TCG\Voyager\Models\Sms;

if(!function_exists('Kavenegar')){
    function Kavenegar($phone, $message, $override = false){

        $sms = Sms::first();
        if($sms->stock > 0 || $override){
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.kavenegar.com/v1/" . config('constants.SMS_API') . "/verify/lookup.json",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => [
                    'receptor' => $phone,
                    'token' => $message,
                    'template' => "cafehub",
                ],
            ]);
            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response);
            //dd($response);
            if($response->entries && $response->entries[0] && env('APP_DEBUG') == true){
                \Illuminate\Support\Facades\Log::info($response->entries[0]->statustext);
            }

            if($response->return && env('APP_DEBUG') == true){
                \Illuminate\Support\Facades\Log::info("SMS / code" . $response->return->status . " : " . $response->return->message);
            }

            if($response->return->status == 200){
                $sms->sends += 1;
                $sms->totalsend += 1;
                $sms->stock -= 1;
                $sms->save();
            }

            return $response;
        } else {
            return false;
        }
    }
}

if(!function_exists('fa_number')){
    function fa_number($number, $flip = false){
        if(empty($number)){
            return '۰';
        }

        if(\app()->getLocale() !== 'fa')
            return $number;

        $en = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
        $fa = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"];
        if($flip){
            return str_replace($fa, $en, $number);
        } else {
            return str_replace($en, $fa, $number);
        }

    }
}

if(!function_exists('json_printer')){
    function json_printer($data){
        $html = '<div class="tablesimpleresponsive">
          <table class="tablesimple" style="min-width:auto">
            <thead>
              <tr>
                <th>' . __('hotdesk.read_table_title') . '</th>
                <th>' . __('hotdesk.read_table_info') . '</th>
              </tr>
            </thead>
            <tbody>';
        foreach($data as $keydata => $dataof){
            $html .= '<tr>
                <td>' . $keydata . '</td>
                <td>';
            if(is_array($dataof)){
                $html .= json_printer($dataof);
            } else {
                try {
                    $decrypted = Crypt::decryptString($dataof);
                    if(is_serialized_string($decrypted)){
                        $decrypted = unserialize($decrypted);
                        if(is_object($decrypted)){
                            $html .= json_printer($decrypted);
                        }

                    }
                } catch(Illuminate\Contracts\Encryption\DecryptException $e){
                    $decrypted = $dataof;
                }
                if(!is_object($decrypted)){
                    $html .= '<span>' . $decrypted . '</span>';
                }

            }
            $html .= '</td>
              </tr>';
        }
        $html .= '</tbody>
          </table>
      </div>
    </div>';
        return $html;
    }
}

if(!function_exists('is_serialized_string')){
    function is_serialized_string($string){
        return ($string == 'b:0;' || @unserialize($string) !== false);
    }
}

if(!function_exists('_response')){
    /**
     * @param null $data
     * @param string|null $message
     * @param bool $status
     * @param int $code
     * @return JsonResponse
     */
    function _response($data = null, string $message = null, bool $status = true, $code = 200): JsonResponse{
        return response()->json([
            "data" => $data ?? [],
            "message" => $message ?? "",
            "status" => $status,
        ], $code ?? 200);
    }
}

if(!function_exists('get_image')){
    function get_image($image){
        if(!isset($image) || $image == ""){
            return "";
        }

        if(substr_count($image, 'http') == 0){
            if(substr_count($image, 'storage') > 0){
                if(substr_count($image, '/storage') > 0){
                    $image = config('app.url') . $image;
                } else {
                    $image = config('app.url') . '/' . $image;
                }

            } else {
                $image = config('app.url') . '/storage/' . $image;
            }
        }

        return preg_replace("/ /", "%20", $image);
    }
}

if(!function_exists('get_cropped_image')){
    function get_cropped_image($image, $flag = false){

        if(!isset($image) || $image == ""){
            return "";
        }

        $image = explode(".", $image);

        if($flag)
            $image[0] .= "-$flag"; else
            $image[0] .= "-cropped";
        $image = implode(".", $image);

        if(substr_count($image, 'http') == 0){
            if(substr_count($image, 'storage') > 0){
                if(substr_count($image, '/storage') > 0){
                    $image = config('app.url') . $image;
                } else {
                    $image = config('app.url') . '/' . $image;
                }

            } else {
                $image = config('app.url') . '/storage/' . $image;
            }
        }

        return preg_replace("/ /", "%20", $image);
    }
}

if(!function_exists("watermarkPhoto")){

    function watermarkPhoto(string $originalFilePath, string $filePath2Save, string $watermark_path){

        //        $watermark_path = 'photos/watermark.png';
        //        if(File::exists($watermark_path)){
        if(Storage::disk('public')->exists($watermark_path)){
            $watermarkImg = Image::make(Storage::disk("public")->get($watermark_path));
            $img = Image::make(Storage::disk("public")->get($originalFilePath));
            $wmarkWidth = $watermarkImg->width();
            $wmarkHeight = $watermarkImg->height();

            $imgWidth = $img->width();
            $imgHeight = $img->height();

            $x = 0;
            $y = 0;
            while($y <= $imgHeight){
                $img->insert(Storage::disk("public")->get($watermark_path), 'top-left', $x, $y);
                $x += $wmarkWidth;
                if($x >= $imgWidth){
                    $x = 0;
                    $y += $wmarkHeight;
                }
            }
            $img->save($filePath2Save);

            $watermarkImg->destroy();
            $img->destroy(); //  to free memory in case you have a lot of images to be processed
        } else {
            return false;
        }
        return $filePath2Save;
    }
}

if(!function_exists('meta')){
    function meta($meta_name = false){
        if($meta_name){

        } else {
            $metas = Meta::all();
        }

        $return = "";
        foreach($metas as $meta){
            $return .= '<meta name="' . $meta->name . '" content="' . $meta->content . '">';
        }

        return $return;
    }
}

if(!function_exists('locale_route')){
    function locale_route($name, $parameters = [], $absolute = true){
        if(is_array($parameters)){
            return route($name, array_merge([App::getLocale()], $parameters), $absolute);
        } else {
            return route($name, [App::getLocale(), $parameters], $absolute);
        }
    }
}

if(!function_exists('adjustBrightness')){

    function adjustBrightness($hexCode, $adjustPercent){
        if(strlen($hexCode) == 0)
            return "";
        $color = hexToHsl($hexCode);
        $color[2] *= $adjustPercent;
        return hslToHex($color);
    }

    function hexToHsl($hex){
        $hex = str_replace("#", "", $hex);
        $hex = [$hex[0] . $hex[1], $hex[2] . $hex[3], $hex[4] . $hex[5]];
        $rgb = array_map(function($part){
            return hexdec($part) / 255;
        }, $hex);

        $max = max($rgb);
        $min = min($rgb);

        $l = ($max + $min) / 2;

        if($max == $min){
            $h = $s = 0;
        } else {
            $diff = $max - $min;
            $s = $l > 0.5 ? $diff / (2 - $max - $min) : $diff / ($max + $min);

            switch($max){
                case $rgb[0]:
                    $h = ($rgb[1] - $rgb[2]) / $diff + ($rgb[1] < $rgb[2] ? 6 : 0);
                    break;
                case $rgb[1]:
                    $h = ($rgb[2] - $rgb[0]) / $diff + 2;
                    break;
                case $rgb[2]:
                    $h = ($rgb[0] - $rgb[1]) / $diff + 4;
                    break;
            }

            $h /= 6;
        }

        return [$h, $s, $l];
    }

    function hslToHex($hsl){
        [$h, $s, $l] = $hsl;

        if($s == 0){
            $r = $g = $b = 1;
        } else {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;

            $r = hue2rgb($p, $q, $h + 1 / 3);
            $g = hue2rgb($p, $q, $h);
            $b = hue2rgb($p, $q, $h - 1 / 3);
        }

        return "#" . rgb2hex($r) . rgb2hex($g) . rgb2hex($b);
    }

    function hue2rgb($p, $q, $t){
        if($t < 0)
            $t += 1;
        if($t > 1)
            $t -= 1;
        if($t < 1 / 6)
            return $p + ($q - $p) * 6 * $t;
        if($t < 1 / 2)
            return $q;
        if($t < 2 / 3)
            return $p + ($q - $p) * (2 / 3 - $t) * 6;

        return $p;
    }

    function rgb2hex($rgb){
        return str_pad(dechex($rgb * 255), 2, '0', STR_PAD_LEFT);
    }
}

function getFile($file){
    $json = json_decode($file);
    if(!is_array($json))
        return "";
    if(count($json) > 0){
        return Storage::url($json[0]->download_link);
    }
    return '';
}

function calculate_cart($carts, Client $client, &$return_wallet, $need_save = true){

    $wallet_price = $client->wallet;
    $price = 0;

    foreach($carts as $cart){

        $item_price = $cart->amount;

        if($cart->item->use_wallet){

            if($item_price >= $wallet_price){
                //                    $wallet_price += $item_price - $client->wallet;
                $price += $item_price - $wallet_price;

                $wallet_price = 0;

            } else {

                $wallet_price -= $item_price;

                $price += 0;
            }

        } else {
            $price += $item_price;
        }
    }

    $return_wallet = $wallet_price;

    if($need_save){
        $client->wallet = $wallet_price;
        $client->save();
    }

    return $price;
}

function send_telegram_messages(string $text){

    $telegram = Telegram::query()->where('is_active', true)->get();

    foreach($telegram as $tlg){
        Http::post(config('constants.TELEGRAM') . "sendMessage", [
            //            "chat_id" => '95634965',
            'text' => $text,
            "chat_id" => $tlg->chat_id,
        ]);
    }
}
