<?php

namespace App\Http\Controllers;


use App\Models\Autor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AutorController extends Controller
{
    private function validateRequest(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|min:3|max:100|regex:/^[a-z ]*$/i',
        ];

        $messages = [
            'nombre.required' => 'El campo nombre es requerido',
            'nombre.min' => 'El campo nombre debe contener al menos 3 caracteres',
            'nombre.regex' => 'El campo nombre no debe contener números',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function index()
    {
        $autores = Autor::all();
        return response()->json($autores);
    }
    public function store(Request $request)
    {
        $validator = $this->validateRequest($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            $autor = Autor::create([
                'nombre' => strtolower($request->get('nombre')),
            ]);
            return response()->json($autor, 201);
        }
    }

    public function show($id)
    {
        $autor = Autor::find($id);

        if (!$autor) {
            return response()->json(['error' => 'Autor no encontrado'], 404);
        }

        return response()->json($autor);
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validateRequest($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            $autor = Autor::find($id);

            if (!$autor) {
                return response()->json(['error' => 'Autor no encontrado'], 404);
            }

            $autor->update([
                'nombre' => strtolower($request->get('nombre')),
            ]);

            return response()->json($autor, 200);
        }
    }

    public function destroy($id)
    {
        $autor = Autor::find($id);

        if (!$autor) {
            return response()->json(['error' => 'Autor no encontrado'], 404);
        }

        $autor->delete();

        return response()->json(null, 204);
    }
}