<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;

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
}
