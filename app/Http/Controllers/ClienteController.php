<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return response()->json($clientes);
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|min:3|max:100|regex:/^[a-z ]*$/i',
            'email' => 'required|email|unique:clientes,email',
            'celular' => 'required|regex:/^7\d{7}$/',
        ];

        $messages = [
            'nombre.required' => 'El campo nombre es requerido',
            'nombre.min' => 'El campo nombre debe contener al menos 3 caracteres',
            'nombre.regex' => 'El campo nombre no debe contener números',
            'email.required' => 'El campo email es requerido',
            'email.email' => 'El campo email debe ser una dirección de correo electrónico válida',
            'email.unique' => 'El email ya está en uso',
            'celular.required' => 'El campo celular es requerido',
            'celular.regex' => 'El campo celular debe contener exactamente 8 dígitos y comenzar con 7',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            $autor = Cliente::create([
                'nombre' => strtolower($request->get('nombre')),
                'email' => $request->get('email'),
                'celular' => $request->get('celular'),
            ]);
            return response()->json($autor, 201);
        }
    }

    public function show($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        return response()->json($cliente);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'nombre' => 'required|string|min:3|max:100|regex:/^[a-z ]*$/i',
            'celular' => 'required|regex:/^7\d{7}$/',
        ];

        $messages = [
            'nombre.required' => 'El campo nombre es requerido',
            'nombre.min' => 'El campo nombre debe contener al menos 3 caracteres',
            'nombre.regex' => 'El campo nombre no debe contener números',
            'celular.required' => 'El campo celular es requerido',
            'celular.regex' => 'El campo celular debe contener exactamente 8 dígitos y comenzar con 7',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        } else {
            $autor = Cliente::find($id);

            if (!$autor) {
                return response()->json(['error' => 'Autor no encontrado'], 404);
            }

            $autor->update([
                'nombre' => strtolower($request->get('nombre')),
                'celular' => $request->get('celular'),
            ]);

            return response()->json($autor, 200);
        }
    }

    public function destroy($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        $cliente->delete();

        return response()->json(null, 204);
    }
}
