<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Resources\ItemCollection;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Event;
use App\Models\Item;
use App\Models\ReservEvent;
use App\Models\SupportSite;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller {

    public function __invoke(Request $request) {

        if($request->has('table')){
            $table = $request->table;

            session(['table' => $table]);
        } else {
            \session()->remove('table');
        }

        $items = Item::query()->with('category')->get()->groupBy('category_id')->sortByDesc('category.sort');

        $events = Event::query()->where('status', true)->where('capacity', '>', 0)
            ->orderByDesc('created_at')->get();
        $categories = Category::all();

        $category_items = \App\Models\Category::query()->orderBy('sort')->get();

        $always = false;

        if (auth()->guard('clients')->check()) {

            $client_id = auth()->guard('clients')->user()->id;
            $always = Item::query()->whereHas('bought', function (Builder $query) use ($client_id) {
                $query->where('client_id', $client_id)->orderByDesc("count")->take(4);
            })->get();
            $carts = Cart::query()->where('client_id', $client_id)->get();
            $reserve_event = ReservEvent::query()->where('client_id', $client_id)->get();

            foreach($carts as $cart){
                if($cart->item && !$cart->item->is_available)
                    $cart->delete();
            }

            $carts = Cart::query()->where('client_id', $client_id)->get();

        } else {

            $carts = collect();
            $reserve_event = ReservEvent::query()->where('ip', \request()->ip())->get();

        }

//        die(json_encode($items));


        $has_reserve = Session::has('reserve_event');
        Session::remove('reserve_event');


        return view('pages.home', compact('items', 'events', 'always', 'categories', 'carts' ,
            'reserve_event' , 'category_items' , 'has_reserve'));
    }

    public function contact_us(){
        return view('pages.contact_us');
    }

    public function contact_us_submit(Request $request){

        SupportSite::query()->create([
           'name' => $request->name,
           'caption' => $request->caption,
           'numbercall' => $request->numbercall,
        ]);

        return redirect()->back()->with('message' , 'success');
    }

}
