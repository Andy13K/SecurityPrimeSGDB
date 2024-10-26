<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProyectoTable extends Migration
{
    public function up()
    {
        Schema::create('proyecto', function (Blueprint $table) {
            $table->id('no_proyecto');
            $table->string('nombre', 100);
            $table->string('descripcion', 64);
            $table->date('fecha_inicio')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->date('fecha_fin');
            $table->unsignedBigInteger('no_tipo_entorno');
            $table->unsignedBigInteger('no_equipo');
            $table->unsignedBigInteger('no_cliente');
            $table->decimal('mano_obra', 15, 2);
            $table->timestamps();

            $table->foreign('no_tipo_entorno')->references('no_entorno')->on('tipo_entorno');
            $table->foreign('no_equipo')->references('no_equipo')->on('equipo_trabajo');
            $table->foreign('no_cliente')->references('no_cliente')->on('cliente');
        });
    }

    public function down()
    {
        Schema::dropIfExists('proyecto');
    }
}
