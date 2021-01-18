<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id_user');
      			$table->integer('id_entite');
      			$table->integer('access_level');
            $table->integer('access_level_admin')->nullable();
      			$table->string('prenom');
      			$table->string('nom');
      			$table->string('tel');
      			$table->string('email');
      			$table->string('password');
      			$table->integer('etat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
