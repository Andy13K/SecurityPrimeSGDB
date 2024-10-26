<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadoTareaTable extends Migration
{
    public function up()
    {
        Schema::create('estado_tarea', function (Blueprint $table) {
            $table->id('no_estado');
            $table->string('nombre', 64);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estado_tarea');
    }
}
