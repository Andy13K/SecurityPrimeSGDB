<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEspecializacionTable extends Migration
{
    public function up()
    {
        Schema::create('especializacion', function (Blueprint $table) {
            $table->id('no_especializacion');
            $table->string('nombre', 64);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('especializacion');
    }
}
