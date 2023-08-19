<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfertas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ofertas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('titulo');
            $table->unsignedBigInteger('sueldo');
            $table->unsignedBigInteger('ciudad_id');
            $table->unsignedBigInteger('area_id');
            $table->unsignedBigInteger('user_id');
            $table->smallInteger('vacantes');
            $table->text('descripcion');
            $table->smallInteger('experiencia');
            $table->smallInteger('contrato');
            $table->smallInteger('estado');
            $table->foreign('area_id')->references('id')->on('area');
            $table->foreign('ciudad_id')->references('id')->on('ciudad');
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
        Schema::dropIfExists('ofertas');
    }
}
