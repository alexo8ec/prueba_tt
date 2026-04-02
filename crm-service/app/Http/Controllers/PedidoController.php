<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $pedidos = Pedido::with(['detalles', 'cliente'])
                ->when($request->estado, fn($q) => $q->where('estado', $request->estado))
                ->when($request->cliente_id, fn($q) => $q->where('cliente_id', $request->cliente_id))
                ->when($request->fecha_desde, fn($q) => $q->whereDate('created_at', '>=', $request->fecha_desde))
                ->when($request->fecha_hasta, fn($q) => $q->whereDate('created_at', '<=', $request->fecha_hasta))
                ->when($request->cliente, function ($q) use ($request) {
                    $q->whereHas('cliente', function ($q2) use ($request) {
                        $q2->where('nombre', 'like', '%' . $request->cliente . '%');
                    });
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($pedidos);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener pedidos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'descripcion' => 'required|string',
            'monto' => 'required|numeric',
            'estado' => 'required|in:pendiente,completado,cancelado',
            'detalles' => 'required|array|min:1',
        ]);

        try {
            $pedido = Pedido::create([
                'cliente_id' => $request->cliente_id,
                'descripcion' => $request->descripcion,
                'monto' => $request->monto,
                'estado' => $request->estado,
                'detalles' => $request->detalles,
            ]);
            return response()->json($pedido, 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear pedido',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $pedido = Pedido::with(['detalles', 'cliente'])->find($id);

        if (!$pedido) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pedido no encontrado'
            ], 404);
        }

        return response()->json($pedido);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pedido no encontrado'
            ], 404);
        }

        $request->validate([
            'descripcion' => 'sometimes|string',
            'monto' => 'sometimes|numeric',
            'estado' => 'sometimes|in:pendiente,completado,cancelado',
            'detalles' => 'sometimes|array|min:1',
        ]);

        try {
            $pedido->update($request->only(['descripcion', 'monto', 'estado', 'detalles']));
            return response()->json([
                'status' => 'success',
                'message' => 'Pedido actualizado correctamente',
                'data' => $pedido
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar pedido',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pedido no encontrado'
            ], 404);
        }

        $pedido->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Pedido eliminado'
        ]);
    }
}
