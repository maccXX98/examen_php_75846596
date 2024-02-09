<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Prestamo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('libro_id')->nullable();
            ;
            $table->unsignedBigInteger('cliente_id')->nullable();
            ;
            $table->date('fecha_prestamo')->default(DB::raw('CURRENT_DATE'));
            $table->integer('dias_prestamo')->unsigned()->nullable();
            $table->string('estado');
            $table->timestamps();

            $table->foreign('libro_id')->references('id')->on('libros');
            $table->foreign('cliente_id')->references('id')->on('clientes');
        });
    }

    public function down()
    {
        Schema::dropIfExists('prestamos');
    }

}
