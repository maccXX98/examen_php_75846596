<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    private $rules = [
        'titulo' => 'required|string|max:255',
        'autor_id' => 'nullable|integer|exists:autores,id',
        'lote' => 'required|integer',
        'descripcion' => 'required|string',
    ];

    private $messages = [
        'titulo.required' => 'El campo título es requerido',
        'autor_id.exists' => 'El autor especificado no existe',
        'lote.required' => 'El campo lote es requerido',
        'lote.integer' => 'El campo lote debe ser un número entero',
        'descripcion.required' => 'El campo descripción es requerido',
    ];

    public function index()
    {
        $libros = Libro::join('autores', 'libros.autor_id', '=', 'autores.id')
            ->select('libros.*', 'autores.nombre as autor_nombre')
            ->orderBy('libros.created_at', 'desc')
            ->get();
    
        return response()->json($libros);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            $libro = Libro::create([
                'titulo' => strtolower($request->get('titulo')),
                'autor_id' => $request->get('autor_id'),
                'lote' => $request->get('lote'),
                'descripcion' => $request->get('descripcion'),
            ]);
            return response()->json($libro, 201);
        }
    }

    public function show($id)
    {
        $libro = Libro::find($id);

        if (!$libro) {
            return response()->json(['error' => 'Libro no encontrado'], 404);
        }

        return response()->json($libro);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            $libro = Libro::find($id);

            if (!$libro) {
                return response()->json(['error' => 'Libro no encontrado'], 404);
            }

            $libro->update([
                'titulo' => strtolower($request->get('titulo')),
                'autor_id' => $request->get('autor_id'),
                'lote' => $request->get('lote'),
                'descripcion' => $request->get('descripcion'),
            ]);

            return response()->json($libro, 200);
        }
    }

    public function destroy($id)
    {
        $libro = Libro::find($id);

        if (!$libro) {
            return response()->json(['error' => 'Libro no encontrado'], 404);
        }

        $libro->delete();

        return response()->json(null, 204);
    }
}
