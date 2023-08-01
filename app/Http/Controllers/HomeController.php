<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ItemResource;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller{

    public function index(Request $request){

        $search = $request->search;
        $category = $request->category;

        if($search)
            $items = Item::query()->with('category')->where('name', 'like', "%$search%")->get()
                ->groupBy('category_id');
        elseif($category)
            $items = Item::query()->whereHas('category' , function(Builder $query) use($category) {
                $query->where('id' , $category);
            })->get()->groupBy('category_id');
        else
            $items = Item::query()->with('category')->get()
                ->groupBy('category_id');

        $categories = Category::all()->sortBy('id');



        return view('pages.home', compact('items', 'categories'));
    }

}
