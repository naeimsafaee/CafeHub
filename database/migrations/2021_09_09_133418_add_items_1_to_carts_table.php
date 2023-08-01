<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItems1ToCartsTable extends Migration
{

    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->text('address')->after('type');
        });
    }


    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            //
        });
    }
}
