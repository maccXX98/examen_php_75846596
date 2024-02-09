<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Autor;
use App\Models\Libro;
use App\Models\Cliente;
use App\Models\Prestamo;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Autor::factory(6)->create();
        Libro::factory(500)->create();
        Cliente::factory(6)->create();
        Prestamo::factory(1000)->create();
    }
}
