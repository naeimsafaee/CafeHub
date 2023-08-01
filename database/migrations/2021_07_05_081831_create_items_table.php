<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration{

    public function up(){
        Schema::create('items', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('price');
            $table->text('image')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->integer('rate')->default(0);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(){
        Schema::dropIfExists('items');
    }
}
