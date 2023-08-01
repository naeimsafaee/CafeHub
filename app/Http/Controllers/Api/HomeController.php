<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;
use App\Http\Resources\ItemCollection;
use App\Models\ClientItem;
use App\Models\Event;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class HomeController extends Controller{

    public function index(){



        $items = Item::all();
        $event = Event::query()->where('status' , true)->get();


//        $always = ClientItem::query()->where('client_id' , auth()->guard('api')->user()->id)->orderByDesc("count")->take(4)->get();

        $client_id = auth()->guard('api')->user()->id;

        $always = Item::query()->whereHas('bought' , function(Builder $query) use($client_id) {
            $query->where('client_id' , $client_id)->orderByDesc("count")->take(4);
        })->get();

        //->collection->groupBy('category_id')
        return _response([
            "items" => (new ItemCollection($items)),
            "event" => new EventCollection($event),
            "always" => new ItemCollection($always),
        ]);
    }

    public function store(Request $request){
        //
    }

    public function show($id){
        //
    }

    public function update(Request $request, $id){
        //
    }

    public function destroy($id){
        //
    }
}
