<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration{

    public function up(){
        Schema::create('clients', function(Blueprint $table){
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();
            $table->text('password')->nullable();
            $table->boolean('is_verify')->default(0);
            $table->string('code')->nullable();
            $table->timestamps();

        });
    }

    public function down(){
        Schema::dropIfExists('clients');
    }
}
