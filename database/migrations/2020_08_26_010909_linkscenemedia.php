<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Linkscenemedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linkscenemedia', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('id_scene');
			$table->integer('id_media');
			$table->integer('priorite');
			$table->integer('duree');
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
        Schema::dropIfExists('linkscenemedia');
    }
}
