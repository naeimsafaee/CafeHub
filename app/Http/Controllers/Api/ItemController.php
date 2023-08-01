<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemCollection;
use App\Models\ClientItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller{

    public function index(Request $request){

        $items = Item::query();
        if($request->has('category_id')){
            $items = $items->where('category_id', $request->category_id);
        }

        $items = $items->get();


        return _response((new ItemCollection($items))->collection->groupBy('category_id'));
    }

    public function store(Request $request){
        //
    }

    public function show($id){


    }

    public function update(Request $request, $id){
        //
    }

    public function destroy($id){
        //
    }
}
