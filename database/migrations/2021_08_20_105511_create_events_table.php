<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration{

    public function up(){
        Schema::create('events', function(Blueprint $table){
            $table->id();
            $table->string('title');
            $table->integer('capacity')->default(0);
            $table->integer('price');
            $table->timestamp('time_at');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('events');
    }
}
