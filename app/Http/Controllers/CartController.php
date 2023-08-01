<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller {

    public function index() {

        $items = Item::query()->whereHas('cart', function(Builder $query) {
            $query->where('ip', \request()->ip());
        })->with('cart')->get();

        return view('pages.order', compact('items'));
    }

    public function address() {
        return view('cart.address');
    }

    public function add_to_cart($id) {

        $item = Item::query()->findOrFail($id);

        Cart::query()->updateOrCreate(
            [
                "ip" => \request()->ip(),
                "item_id" => $item->id,
            ], [
                "count" => DB::raw('count+1'),
                "item_id" => $item->id,
                "ip" => \request()->ip()
            ]
        );
        return _response("", "ok");
    }

    public function remove_from_cart($id) {

        $cart = Cart::query()->where([
            'item_id' => $id,
            'ip' => \request()->ip()
        ])->delete();

        return redirect()->route('cart');
    }

}
