<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pedido;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {

            // 🔥 Base query con filtros
            $query = Pedido::query();

            if ($request->filled('fecha_desde')) {
                $query->whereDate('created_at', '>=', $request->fecha_desde);
            }

            if ($request->filled('fecha_hasta')) {
                $query->whereDate('created_at', '<=', $request->fecha_hasta);
            }

            // 🔹 Total pedidos
            $totalPedidos = (clone $query)->count();

            // 🔹 Pedidos por estado
            $pedidosPorEstado = (clone $query)
                ->select('estado', DB::raw('count(*) as total'))
                ->groupBy('estado')
                ->get();

            // 🔹 Clientes activos (con pedidos filtrados)
            $clientesActivos = Cliente::whereHas('pedidos', function ($q) use ($request) {
                if ($request->filled('fecha_desde')) {
                    $q->whereDate('created_at', '>=', $request->fecha_desde);
                }
                if ($request->filled('fecha_hasta')) {
                    $q->whereDate('created_at', '<=', $request->fecha_hasta);
                }
            })->count();

            // 🔹 Pedidos por día (CORREGIDO)
            $pedidosPorDia = (clone $query)
                ->select(
                    DB::raw('CAST(created_at as DATE) as fecha'),
                    DB::raw('count(*) as total')
                )
                ->groupBy(DB::raw('CAST(created_at as DATE)'))
                ->orderBy('fecha')
                ->get();

            // 🔹 Pedidos por mes
            $pedidosPorMes = (clone $query)
                ->select(
                    DB::raw('YEAR(created_at) as anio'),
                    DB::raw('MONTH(created_at) as mes'),
                    DB::raw('count(*) as total')
                )
                ->groupBy(
                    DB::raw('YEAR(created_at)'),
                    DB::raw('MONTH(created_at)')
                )
                ->orderBy(DB::raw('YEAR(created_at)'))
                ->orderBy(DB::raw('MONTH(created_at)'))
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_pedidos' => $totalPedidos,
                    'pedidos_por_estado' => $pedidosPorEstado,
                    'clientes_activos' => $clientesActivos,
                    'pedidos_por_dia' => $pedidosPorDia,
                    'pedidos_por_mes' => $pedidosPorMes,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error en dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
