<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use Closure;
use Illuminate\Http\Request;

class RemoveCartMiddleware {

    public function handle(Request $request, Closure $next) {

        if(auth()->guard('clients')->check()){
            $client_id = auth()->guard('clients')->user()->id;

            $carts = Cart::query()->where('client_id', $client_id)->get();

            foreach($carts as $cart){
                if($cart->item->is_breakfast){
                    $cart->delete();
                }
            }
        }

        return $next($request);
    }
}
