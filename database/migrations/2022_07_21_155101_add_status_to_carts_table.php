<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToCartsTable extends Migration {

    public function up() {
        Schema::table('carts', function(Blueprint $table) {
            $table->enum('status' , ['active' , 'de_active'])->default('active');
        });
    }

    public function down() {
        Schema::table('carts', function(Blueprint $table) {
            //
        });
    }
}
