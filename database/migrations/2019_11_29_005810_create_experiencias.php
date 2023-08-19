<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperiencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiencias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('empresa');
            $table->unsignedinteger('sector');
            $table->unsignedinteger('subsector');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->boolean('trabajo_aqui');
            $table->string('cargo');
            $table->text('responsabilidades');
            $table->string('telefono', 20);
            $table->unsignedinteger('ciudad');
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
        Schema::dropIfExists('experiencias');
    }
}
