<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRememberTokenToClientsTable extends Migration
{

    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->rememberToken();
        });
    }


    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            //
        });
    }
}
