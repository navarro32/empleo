<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatosBasicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datos_basicos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->char('telefono', 20);
            $table->char('celular', 20);
            $table->char('documento', 20);
            $table->date('fecha_nacimiento');
            $table->char('genero', 2);
            $table->char('estado_civil', 20);
            $table->integer('departamento');
            $table->integer('ciudad');
            $table->string('direccion')->nullable();
            $table->string('ruta_avatar')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('datos_basicos');
    }
}
