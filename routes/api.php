<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PrestamoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('autores', AutorController::class);
Route::apiResource('libros', LibroController::class);
Route::apiResource('clientes', ClienteController::class);

// Rutas personalizadas para PrestamoController
Route::get('/prestamos/vencidos', [PrestamoController::class, 'getClientesConLibrosVencidos']);
Route::get('/prestamos/semana', [PrestamoController::class, 'getPrestamosPorSemana']);
Route::get('/prestamos/mes',[PrestamoController::class, 'getPrestamosPorMes']);
Route::post('/prestamos/registrar', [PrestamoController::class, 'registrarPrestamo']);
Route::post('/prestamos/devolucion/{id}', [PrestamoController::class, 'registrarDevolucion']);


// Rutas CRUD para PrestamoController
Route::apiResource('prestamos', PrestamoController::class)->except(['store']);