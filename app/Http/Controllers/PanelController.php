<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;

class PanelController extends Controller
{
    public function admin()
    {
        return view('admin.inicio', [
            'totalClientes' => Cliente::where('rol', 'cliente')->count(),
            'totalProductos' => Producto::count(),
            'totalPedidos' => Pedido::count(),
            'ventas' => Pedido::where('estado', '!=', 'Cancelado')->sum('total'),
            'ultimosPedidos' => Pedido::with('cliente')
                ->orderByDesc('fecha_pedido')
                ->limit(5)
                ->get(),
        ]);
    }

    public function cliente(Request $request)
    {
        $pedidos = $request->user()
            ->pedidos()
            ->withCount('detalles')
            ->orderByDesc('fecha_pedido')
            ->get();

        return view('cliente.inicio', compact('pedidos'));
    }
}
