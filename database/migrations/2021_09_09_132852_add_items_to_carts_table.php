<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemsToCartsTable extends Migration{

    public function up(){
        Schema::table('carts', function(Blueprint $table){
            $table->integer('type')->after('count')->default(0);
        });
    }

    public function down(){
        Schema::table('carts', function(Blueprint $table){
            //
        });
    }
}
