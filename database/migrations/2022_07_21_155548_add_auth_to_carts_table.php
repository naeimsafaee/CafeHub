<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuthToCartsTable extends Migration {

    public function up() {
        Schema::table('carts', function(Blueprint $table) {
            $table->text('auth')->nullable();
        });
    }

    public function down() {
        Schema::table('carts', function(Blueprint $table) {
            //
        });
    }
}
