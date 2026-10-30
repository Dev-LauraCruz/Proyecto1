<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class TiendaController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $categoria = $request->query('categoria');
        $orden = $request->query('orden', 'nombre');

        $productos = Producto::with('categoria')
            ->where('estado', 1)
            ->when($q !== '', function ($consulta) use ($q) {
                $consulta->where(function ($w) use ($q) {
                    $w->where('nombre_producto', 'like', "%{$q}%")
                        ->orWhere('marca', 'like', "%{$q}%");
                });
            })
            ->when($categoria, fn ($consulta) => $consulta->where('id_categoria', $categoria))
            ->when($orden === 'precio_asc', fn ($consulta) => $consulta->orderBy('precio'))
            ->when($orden === 'precio_desc', fn ($consulta) => $consulta->orderByDesc('precio'))
            ->when(! in_array($orden, ['precio_asc', 'precio_desc']), fn ($consulta) => $consulta->orderBy('nombre_producto'))
            ->get();

        return view('cliente.inicio', [
            'productos' => $productos,
            'categorias' => Categoria::where('estado', 1)->orderBy('nombre_categoria')->get(),
            'enComparacion' => session('comparar', []),
        ]);
    }
}
