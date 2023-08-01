<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Item;
use App\Models\ReservEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddController extends Controller{

    public function add_to_cart($id){

        $item = Item::query()->findOrFail($id);

        if(auth()->guard('clients')->check()){
            Cart::query()->updateOrCreate([
                'item_id' => $item->id,
                "client_id" => auth()->guard('clients')->user()->id,
            ], [
                "count" => DB::raw('count+1'),
                "item_id" => $item->id,
                "client_id" => auth()->guard('clients')->user()->id,
            ]);

            ReservEvent::query()->where([
                "client_id" => auth()->guard('clients')->user()->id,
            ])->delete();
        } else {
            Cart::query()->updateOrCreate([
                'item_id' => $item->id,
                "ip" => \request()->ip(),
            ], [
                "count" => DB::raw('count+1'),
                "item_id" => $item->id,
                "ip" => \request()->ip(),
            ]);

            ReservEvent::query()->where([
                "ip" => \request()->ip(),
            ])->delete();

        }

        return redirect()->back();
    }

    public function destroy($id){
        $item = Item::query()->findOrFail($id);

        Cart::query()->updateOrCreate([
            'item_id' => $item->id,
            "client_id" => auth()->guard('clients')->user()->id,
        ], [
            "count" => DB::raw('count-1'),
            "item_id" => $item->id,
            "client_id" => auth()->guard('clients')->user()->id,
        ]);

        $temp_cart = Cart::query()->where([
            'item_id' => $item->id,
            "client_id" => auth()->guard('clients')->user()->id,
        ])->first();

        if($temp_cart->count <= 0)
            $temp_cart->delete();

        return redirect()->back();
    }


}
