<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUseWalletToTransactionTable extends Migration{

    public function up(){
        Schema::table('transactions', function(Blueprint $table){
            $table->boolean('use_wallet')->default(false);
        });
    }

    public function down(){
        Schema::table('transactions', function(Blueprint $table){
            //
        });
    }
}
