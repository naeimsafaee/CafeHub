<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration{

    public function up(){
        Schema::create('carts', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('client_id');
            $table->ipAddress('ip');
            $table->integer('count')->default(0);
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(){
        Schema::dropIfExists('carts');
    }
}
