<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmountToTransactionTable extends Migration{
    public function up(){
        Schema::table('transactions', function(Blueprint $table){
            $table->integer('wallet_amount')->default(0);
        });
    }

    public function down(){
        Schema::table('transactions', function(Blueprint $table){
            $table->integer('wallet_amount')->default(0);
        });
    }
}
