<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadoTable extends Migration
{
    public function up()
    {
        Schema::create('empleado', function (Blueprint $table) {
            $table->id('no_empleado');
            $table->string('nombre', 64);
            $table->string('telefono', 8);
            $table->string('correo', 64);
            $table->string('direccion', 100);
            $table->unsignedBigInteger('no_equipo');
            $table->unsignedBigInteger('no_especializacion');
            $table->unsignedBigInteger('cargo_no_cargo');
            $table->timestamps();

            // Relaciones
            $table->foreign('no_equipo')->references('no_equipo')->on('equipo_trabajo');
            $table->foreign('no_especializacion')->references('no_especializacion')->on('especializacion');
            $table->foreign('cargo_no_cargo')->references('no_cargo')->on('cargo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('empleado');
    }
}
