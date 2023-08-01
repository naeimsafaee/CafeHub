<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToCafeReservsTable extends Migration{

    public function up(){
        Schema::table('cafe_reservs', function(Blueprint $table){
            $table->boolean('status')->default(false)->after('description');
        });
    }

    public function down(){
        Schema::table('cafe_reservs', function(Blueprint $table){
            //
        });
    }
}
