<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemsToItemsTable extends Migration{

    public function up(){
        Schema::table('items', function(Blueprint $table){
            $table->boolean('use_wallet')->default(false)->after('rate');
        });
    }

    public function down(){
        Schema::table('items', function(Blueprint $table){
            //
        });
    }
}
