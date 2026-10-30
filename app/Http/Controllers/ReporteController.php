<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function ventas(Request $request)
    {
        return view('admin.reporte', $this->datos($request));
    }

    public function pdf(Request $request)
    {
        return Pdf::loadView('pdf.reporte-ventas', $this->datos($request))
            ->setPaper('letter')
            ->download('reporte-ventas-'.now()->format('Y-m-d').'.pdf');
    }

    private function datos(Request $request): array
    {
        $reglas = [
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date'],
            'estado' => ['nullable', 'in:Pendiente,Pagado,Enviado,Entregado,Cancelado'],
        ];

        if ($request->filled('desde')) {
            $reglas['hasta'][] = 'after_or_equal:desde';
        }

        $request->validate($reglas, [
            'desde.date' => 'La fecha inicial no es válida.',
            'hasta.date' => 'La fecha final no es válida.',
            'hasta.after_or_equal' => 'La fecha final no puede ser anterior a la inicial.',
        ]);

        $desde = $request->query('desde');
        $hasta = $request->query('hasta');
        $estado = $request->query('estado');

        // Filtros comunes: sin estado elegido se excluyen los pedidos cancelados
        $filtrar = function ($consulta) use ($desde, $hasta, $estado) {
            $consulta
                ->when(
                    $estado,
                    fn ($q) => $q->where('pedido.estado', $estado),
                    fn ($q) => $q->where('pedido.estado', '!=', 'Cancelado')
                )
                ->when($desde, fn ($q) => $q->whereDate('pedido.fecha_pedido', '>=', $desde))
                ->when($hasta, fn ($q) => $q->whereDate('pedido.fecha_pedido', '<=', $hasta));
        };

        // Ventas por producto
        $consulta = DB::table('detalle_pedido as d')
            ->join('pedido', 'pedido.id_pedido', '=', 'd.id_pedido')
            ->join('producto as p', 'p.id_producto', '=', 'd.id_producto')
            ->leftJoin('categoria as c', 'c.id_categoria', '=', 'p.id_categoria');
        $filtrar($consulta);
        $porProducto = $consulta
            ->groupBy('p.id_producto', 'p.nombre_producto', 'p.marca', 'c.nombre_categoria')
            ->selectRaw('p.nombre_producto, p.marca, c.nombre_categoria as categoria, SUM(d.cantidad) as unidades, SUM(d.subtotal) as ingresos')
            ->orderByDesc('ingresos')
            ->get();

        // Ventas por categoría
        $consulta = DB::table('detalle_pedido as d')
            ->join('pedido', 'pedido.id_pedido', '=', 'd.id_pedido')
            ->join('producto as p', 'p.id_producto', '=', 'd.id_producto')
            ->leftJoin('categoria as c', 'c.id_categoria', '=', 'p.id_categoria');
        $filtrar($consulta);
        $porCategoria = $consulta
            ->groupBy('c.id_categoria', 'c.nombre_categoria')
            ->selectRaw('c.nombre_categoria as categoria, SUM(d.cantidad) as unidades, SUM(d.subtotal) as ingresos')
            ->orderByDesc('ingresos')
            ->get();

        // Pedidos incluidos en el reporte
        $consulta = DB::table('pedido')
            ->join('cliente', 'cliente.id_cliente', '=', 'pedido.id_cliente');
        $filtrar($consulta);
        $pedidos = $consulta
            ->orderByDesc('pedido.fecha_pedido')
            ->select('pedido.id_pedido', 'pedido.fecha_pedido', 'pedido.estado', 'pedido.total', 'cliente.nombre', 'cliente.apellido_p')
            ->get();

        return [
            'porProducto' => $porProducto,
            'porCategoria' => $porCategoria,
            'pedidos' => $pedidos,
            'totales' => [
                'pedidos' => $pedidos->count(),
                'unidades' => (int) $porProducto->sum('unidades'),
                'ingresos' => (float) $pedidos->sum('total'),
                'ticket' => $pedidos->count() ? (float) $pedidos->avg('total') : 0,
            ],
            'desde' => $desde,
            'hasta' => $hasta,
            'estado' => $estado,
            'generado' => now(),
        ];
    }
}
