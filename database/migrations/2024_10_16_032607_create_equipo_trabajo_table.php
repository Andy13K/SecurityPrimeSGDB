<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipoTrabajoTable extends Migration
{
    public function up()
    {
        Schema::create('equipo_trabajo', function (Blueprint $table) {
            $table->id('no_equipo');
            $table->string('nombre', 64);
            $table->string('supervisor', 64);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipo_trabajo');
    }
}
