<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelacionEstadoProyectoTable extends Migration
{
    public function up()
    {
        Schema::create('relacion_estado_proyecto', function (Blueprint $table) {
            $table->unsignedBigInteger('estado_proyecto_no_estado');
            $table->unsignedBigInteger('proyecto_no_proyecto');
            $table->timestamps();

            $table->primary(['estado_proyecto_no_estado', 'proyecto_no_proyecto']);
            $table->foreign('estado_proyecto_no_estado')->references('no_estado')->on('estado_proyecto');
            $table->foreign('proyecto_no_proyecto')->references('no_proyecto')->on('proyecto');
        });
    }

    public function down()
    {
        Schema::dropIfExists('relacion_estado_proyecto');
    }
}
