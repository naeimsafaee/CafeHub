<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model{
    use HasFactory;

    protected $hidden = ["created_at", "updated_at"];

    protected $appends = ["content"];


    public function getContentAttribute(){
        return Item::query()->where('category_id' , $this->id)->select('name')->take(4)->get()->implode('name', ',');
    }

    public function items(){
        return $this->hasMany(Item::class);
    }



}
