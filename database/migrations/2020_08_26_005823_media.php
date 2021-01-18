<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Media extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
          $table->increments('id');
    			$table->integer('id_media');
    			$table->integer('id_entite');
    			$table->string('nom');
    			$table->string('taille');
    			$table->string('type');
          $table->string('resolution');
          $table->string('format');
          $table->text('src');
    			$table->string('type_mime')->nullable()->change();
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
        Schema::dropIfExists('media');
    }
}
