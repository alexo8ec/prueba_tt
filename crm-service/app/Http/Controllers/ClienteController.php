<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $clientes = Cliente::all();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al obtener clientes'], 500);
        }
        return response()->json(['status' => 'success', 'data' => $clientes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'email' => 'required|email|unique:clientes,email',
                'telefono' => 'nullable|string|max:20',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error de validación', 'errors' => $e->errors()], 422);
        }
        return response()->json(['status' => 'success', 'data' => Cliente::create($request->all())]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Cliente no encontrado'], 404);
        }
        return $cliente;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $request->validate([
                'nombre' => 'sometimes|required|string|max:255',
                'apellido' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:clientes,email,' . $id,
                'telefono' => 'nullable|string|max:20',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        }

        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        $cliente->update($request->only(['nombre', 'apellido', 'email', 'telefono']));

        return response()->json([
            'status' => 'success',
            'message' => 'Cliente actualizado correctamente',
            'data' => $cliente
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            Cliente::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Cliente no encontrado'], 404);
        }
        Cliente::destroy($id);
        return response()->json(['status' => 'success', 'message' => 'Cliente eliminado correctamente']);
    }
}
