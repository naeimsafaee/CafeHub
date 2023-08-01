<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCafeReservsTable extends Migration{

    public function up(){
        Schema::create('cafe_reservs', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('name');
            $table->string('phone');
            $table->integer('count')->default(1);
            $table->integer('paid_price')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('cafe_reservs');
    }
}
