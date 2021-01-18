<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Module extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_module');
            $table->interger('id_module_g');
      	    $table->integer('id_entite');
            $table->string('nom');
      	    $table->integer('latitude')->nullable();
            $table->integer('longitude')->nullable();
            $table->integer('state');
            $table->integer('status_module');
            $table->integer('status_screen');
            $table->string('last_ping')->nullable();
            $table->integer('timestamp_last_ping')->nullable();
      		$table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module');
    }
}
