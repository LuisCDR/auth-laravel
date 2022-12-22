<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('usu_ide');
            $table->text('usu_usu')->unique();
            $table->text('usu_pas');
            $table->integer('per_ide');
            $table->integer('est_ado');
            $table->integer('usu_cre');
            $table->timestamp('fec_cre');
            $table->integer('usu_mod');
            $table->timestamp('fec_mod');
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
