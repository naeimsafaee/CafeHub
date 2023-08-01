<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservEvent extends Model{
    use HasFactory;

    protected $fillable = [
        'client_id', 'event_id' ,'count' , 'transaction_id', 'paid' , 'ip'
    ];

    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function getAmountAttribute(){
        return $this->event->price * $this->count;
    }

}
