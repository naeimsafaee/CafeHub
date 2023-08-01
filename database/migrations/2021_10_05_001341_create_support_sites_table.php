<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportSitesTable extends Migration{

    public function up(){
        Schema::create('support_sites', function(Blueprint $table){
            $table->id();
            $table->string('name')->nullable();
            $table->text('caption')->nullable();
            $table->string('numbercall')->nullable();
            $table->timestamps();
        });
    }

    public function down(){
        Schema::dropIfExists('support_sites');
    }
}
