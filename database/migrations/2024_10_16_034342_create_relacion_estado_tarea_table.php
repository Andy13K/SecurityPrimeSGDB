<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelacionEstadoTareaTable extends Migration
{
    public function up()
    {
        Schema::create('relacion_estado_tarea', function (Blueprint $table) {
            $table->unsignedBigInteger('tarea_no_tarea');
            $table->unsignedBigInteger('proyecto_no_proyecto');
            $table->timestamps();

            $table->primary(['tarea_no_tarea', 'proyecto_no_proyecto']);
            $table->foreign('tarea_no_tarea')->references('no_tarea')->on('tarea');
            $table->foreign('proyecto_no_proyecto')->references('no_proyecto')->on('proyecto');
        });
    }

    public function down()
    {
        Schema::dropIfExists('relacion_estado_tarea');
    }
}
