<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecursoProyectoTable extends Migration
{
    public function up()
    {
        Schema::create('recurso_proyecto', function (Blueprint $table) {
            $table->unsignedBigInteger('recurso_no_recurso');
            $table->unsignedBigInteger('proyecto_no_proyecto');
            $table->integer('cantidad_asignada');
            $table->date('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();

            $table->primary(['recurso_no_recurso', 'proyecto_no_proyecto']);
            $table->foreign('recurso_no_recurso')->references('no_recurso')->on('recurso');
            $table->foreign('proyecto_no_proyecto')->references('no_proyecto')->on('proyecto');

        });
    }

    public function down()
    {
        Schema::dropIfExists('recurso_proyecto');
    }
}
