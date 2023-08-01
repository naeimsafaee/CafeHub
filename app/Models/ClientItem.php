<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientItem extends Model{
    use HasFactory;

    protected $fillable = [
        'item_id',
        "client_id",
        "count",
        "status",
        'type',
        'address',
        'transaction_id',
    ];

    protected $appends = ['amount'];

    public function item(){
        return $this->belongsTo(Item::class);
    }

    public function getAmountAttribute(){
        return $this->item->price * $this->count;
    }

}
