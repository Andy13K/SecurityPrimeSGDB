<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecursoTable extends Migration
{
    public function up()
    {
        Schema::create('recurso', function (Blueprint $table) {
            $table->id('no_recurso');
            $table->decimal('precio', 15, 2);
            $table->decimal('costo', 15, 2);
            $table->string('nombre', 64);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recurso');
    }
}
