<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemsToClientsTable extends Migration{

    public function up(){
        Schema::table('clients', function(Blueprint $table){
            $table->integer('wallet')->default(0)->after('code');
        });
    }

    public function down(){
        Schema::table('clients', function(Blueprint $table){
            //
        });
    }
}
