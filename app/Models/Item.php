<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;

class Item extends Model{
    use HasFactory;

    protected $casts = ["category_id" => "int" , 'price' => 'int'];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function cart(){
        return $this->hasMany(Cart::class);
    }

    public function cart_count(){
        if(!auth()->guard('api')->check())
            return false;

        return Cart::query()->where([
            'item_id' => $this->id,
            'client_id' => auth()->guard('api')->user()->id
        ])->first();
    }

    public function bought(){
        return $this->hasMany(ClientItem::class);
    }

    public function getIsBreakfastAttribute($value) {
        if(!$value)
            return false;

        return Carbon::createFromTimeString('12:00')->isPast();
    }

}
