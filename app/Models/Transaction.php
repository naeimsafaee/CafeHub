<?php


namespace App\Models;


class Transaction extends \TCG\Voyager\Models\Transaction{

    protected $fillable = [
        'product_id',
        'from',
        'forsms',
        'transaction_data',
        'status',
        'transaction_date',
        'address_id',
        'wallet_transaction_id',
        'amount',
        'paid',
        'client_id',
        'product_id',
        'options',
        'receive_time',
        'bank_transaction_id',
        'use_wallet',
        'wallet_amount',
    ];

    public function items(){
        return $this->hasMany(ClientItem::class);
    }

    public function event(){
        return $this->hasMany(ReservEvent::class);
    }

}
