<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Groupeusersdroit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groupeusersdroit', function (Blueprint $table) {
            $table->id();
            $table->integer('id_groupeusers');
            $table->string('type_cible');
            $table->integer('id_cible');
            $table->string('droit');
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
        Schema::dropIfExists('groupeusersdroit');
    }
}
