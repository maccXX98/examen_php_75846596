<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Libro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->unsignedBigInteger('autor_id')->nullable();
            $table->integer('lote');
            $table->text('descripcion');
            $table->timestamps();

            $table->foreign('autor_id')->references('id')->on('autores');
        });
    }

    public function down()
    {
        Schema::dropIfExists('libros');
    }

}
