<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Cart extends Model {
    use HasFactory;

    protected $fillable = [
        'ip', 'item_id', 'count', 'client_id', 'address'
    ];

    protected $appends = ['amount'];

    protected static function booted() {
        static::addGlobalScope('auth_check', function(Builder $builder) {
            $builder->whereNull('auth');
        });
    }

    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function getAmountAttribute() {
        return $this->item->price * $this->count;
    }

}
