<?php

namespace App\Http\Controllers;

use App\Models\Producto;

class CompararController extends Controller
{
    private const MAXIMO = 3;

    public function index()
    {
        $ids = session('comparar', []);

        $productos = Producto::with('categoria')
            ->whereIn('id_producto', $ids)
            ->where('estado', 1)
            ->get()
            ->sortBy(fn ($p) => array_search($p->id_producto, $ids))
            ->values();

        return view('cliente.comparar', [
            'productos' => $productos,
            'menor' => $productos->min('precio'),
        ]);
    }

    public function alternar(Producto $producto)
    {
        abort_unless($producto->estado, 404);

        $ids = session('comparar', []);

        if (in_array($producto->id_producto, $ids)) {
            session(['comparar' => array_values(array_diff($ids, [$producto->id_producto]))]);

            return back()->with('exito', 'Quitaste el producto de la comparación.');
        }

        if (count($ids) >= self::MAXIMO) {
            return back()->with('aviso', 'Solo puedes comparar hasta '.self::MAXIMO.' productos a la vez. Quita uno para agregar otro.');
        }

        $ids[] = $producto->id_producto;
        session(['comparar' => $ids]);

        return back()->with('exito', "Agregaste {$producto->nombre_producto} a la comparación.");
    }

    public function vaciar()
    {
        session()->forget('comparar');

        return back();
    }
}
