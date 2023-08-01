<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller{

    public function index(){
        return _response(new CategoryCollection(Category::all()));
    }

    public function show($id){
        return _response(new CategoryResource(Category::query()->findOrFail($id)));
    }

}
