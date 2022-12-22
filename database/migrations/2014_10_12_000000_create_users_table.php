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
            $table->integer('est_ado')->nullable()->default(1);
            $table->integer('usu_cre')->nullable();
            $table->date('fec_cre')->nullable();
            $table->integer('usu_mod')->nullable();
            $table->date('fec_mod')->nullable();
            $table->rememberToken()->nullable();
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
