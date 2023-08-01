<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGarsonCallsTable extends Migration{

    public function up(){
        Schema::create('garson_calls', function(Blueprint $table){
            $table->id();
            $table->integer('table_number');
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('garson_calls');
    }
}
