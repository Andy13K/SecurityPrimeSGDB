<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteTable extends Migration
{
    public function up()
    {
        Schema::create('cliente', function (Blueprint $table) {
            $table->id('no_cliente');
            $table->string('nombre', 64);
            $table->string('direccion', 64);
            $table->string('correo', 64);
            $table->unsignedBigInteger('telefono')->length(8);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cliente');
    }
}
