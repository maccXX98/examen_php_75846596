<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    private function validateRequest(Request $request)
    {
        $rules = [
            'libro_id' => 'nullable|integer|exists:libros,id',
            'cliente_id' => 'nullable|integer|exists:clientes,id',
            'fecha_prestamo' => 'nullable|date',
            'dias_prestamo' => 'nullable|integer',
        ];

        $messages = [
            'libro_id.exists' => 'El libro especificado no existe',
            'cliente_id.exists' => 'El cliente especificado no existe',
            'fecha_prestamo.date' => 'La fecha de préstamo debe ser una fecha válida',
            'dias_prestamo.integer' => 'Los días de préstamo deben ser un número entero',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function index()
    {
        $prestamos = Prestamo::join('libros', 'prestamos.libro_id', '=', 'libros.id')
            ->join('clientes', 'prestamos.cliente_id', '=', 'clientes.id')
            ->select('prestamos.*', 'libros.titulo as libro_titulo', 'clientes.nombre as cliente_nombre')
            ->orderBy('prestamos.created_at', 'desc') // Ordenar por fecha de creación en orden descendente
            ->get();

        return response()->json($prestamos);
    }


    public function show($id)
    {
        $prestamo = Prestamo::find($id);

        if (!$prestamo) {
            return response()->json(['error' => 'Préstamo no encontrado'], 404);
        }

        return response()->json($prestamo);
    }

    public function registrarPrestamo(Request $request)
    {
        $validator = $this->validateRequest($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            $data = $request->all();
            if (empty($data['fecha_prestamo'])) {
                $data['fecha_prestamo'] = now()->format('Y-m-d');
            }
            // Establecer el estado por defecto a "En préstamo"
            $data['estado'] = 'En préstamo';
            $prestamo = Prestamo::create($data);
            return response()->json($prestamo, 201);
        }
    }

    public function registrarDevolucion($id)
    {
        $prestamo = Prestamo::find($id);

        if (!$prestamo) {
            return response()->json(['error' => 'Préstamo no encontrado'], 404);
        }

        $prestamo->estado = 'Devuelto';
        $prestamo->save();

        return response()->json($prestamo, 200);
    }

    public function getClientesConLibrosVencidos()
    {
        $prestamosVencidos = Prestamo::join('libros', 'prestamos.libro_id', '=', 'libros.id')
            ->join('clientes', 'prestamos.cliente_id', '=', 'clientes.id')
            ->select('prestamos.*', 'libros.titulo as libro_titulo', 'clientes.nombre as cliente_nombre')
            ->where('prestamos.estado', 'En préstamo')
            ->whereRaw('DATE_ADD(prestamos.fecha_prestamo, INTERVAL prestamos.dias_prestamo DAY) < CURDATE()')
            ->get();

        return response()->json($prestamosVencidos);
    }

    public function getPrestamosPorSemana()
    {
        $prestamosPorSemana = Prestamo::selectRaw('MONTHNAME(fecha_prestamo) as mes, WEEK(fecha_prestamo) as semana, COUNT(*) as total')
            ->groupBy('mes', 'semana')
            ->get();

        return response()->json($prestamosPorSemana);
    }

    public function getPrestamosPorMes()
    {
        $prestamosPorMes = Prestamo::selectRaw('MONTHNAME(fecha_prestamo) as mes, COUNT(*) as total')
            ->groupBy('mes')
            ->get();

        return response()->json($prestamosPorMes);
    }

}
