<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramsTable extends Migration{

    public function up(){
        Schema::create('telegrams', function(Blueprint $table){
            $table->id();
            $table->string('chat_id');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('telegrams');
    }
}
