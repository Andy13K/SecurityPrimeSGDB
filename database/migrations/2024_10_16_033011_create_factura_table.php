<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturaTable extends Migration
{
    public function up()
    {
        Schema::create('factura', function (Blueprint $table) {
            $table->id('no_factura');
            $table->unsignedBigInteger('proyecto_no_proyecto');
            $table->date('fecha_emision')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->decimal('total', 15, 2);
            $table->string('NIT', 20);
            $table->timestamps();

            $table->foreign('proyecto_no_proyecto')->references('no_proyecto')->on('proyecto');
        });
    }

    public function down()
    {
        Schema::dropIfExists('factura');
    }
}
