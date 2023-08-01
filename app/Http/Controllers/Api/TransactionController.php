<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller{

    public function show($id){
        $transaction = Transaction::query()->findOrFail($id);

        return _response(new TransactionResource($transaction));
    }


}
