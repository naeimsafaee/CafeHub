<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToClientsItemsTable extends Migration{

    public function up(){
        Schema::table('client_items', function(Blueprint $table){
            $table->boolean('status')->default(false)->after('transaction_id');
        });
    }

    public function down(){
        Schema::table('client_items', function(Blueprint $table){
            //
        });
    }
}
