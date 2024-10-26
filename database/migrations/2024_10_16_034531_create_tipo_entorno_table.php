<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateTipoEntornoTable extends Migration
{
    public function up()
    {
        Schema::create('tipo_entorno', function (Blueprint $table) {
            $table->id('no_entorno');
            $table->string('nombre', 64);
            $table->string('descripcion', 256);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tipo_entorno');
    }
}
