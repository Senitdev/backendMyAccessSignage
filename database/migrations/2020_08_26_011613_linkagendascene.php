<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Linkagendascene extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linkagendascene', function (Blueprint $table) {
            $table->increments('id');
      			$table->integer('id_agenda');
      			$table->integer('id_scene');
      			$table->dateTime('date_debut');
      			$table->dateTime('date_fin');
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
        Schema::dropIfExists('linkagendascene');
    }
}
