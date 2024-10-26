<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargoTable extends Migration
{
    public function up()
    {
        Schema::create('cargo', function (Blueprint $table) {
            $table->id('no_cargo');
            $table->string('nombre', 64);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cargo');
    }
}
