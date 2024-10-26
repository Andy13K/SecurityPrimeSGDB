<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareaTable extends Migration
{
    public function up()
    {
        Schema::create('tarea', function (Blueprint $table) {
            $table->id('no_tarea');
            $table->date('fecha_inicio')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->date('fecha_fin');
            $table->string('descripcion', 256);
            $table->unsignedBigInteger('estado_tarea');
            $table->unsignedBigInteger('empleado_no');
            $table->string('comprobacion', 256)->nullable();
            $table->timestamps();

            $table->foreign('estado_tarea')->references('no_estado')->on('estado_tarea');
            $table->foreign('empleado_no')->references('no_empleado')->on('empleado');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tarea');
    }
}
