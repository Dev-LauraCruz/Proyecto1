<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function index(Request $request)
    {
        $items = $this->lineas();

        return view('cliente.carrito', [
            'items' => $items,
            'total' => collect($items)->sum('subtotal'),
            'direcciones' => $request->user()->direcciones()->orderBy('id_direccion')->get(),
        ]);
    }

    public function agregar(Request $request, Producto $producto)
    {
        abort_unless($producto->estado, 404);

        if ($producto->stock <= 0) {
            return back()->withErrors(['carrito' => "{$producto->nombre_producto} está agotado."]);
        }

        $cantidad = max(1, (int) $request->input('cantidad', 1));
        $carrito = session('carrito', []);
        $nueva = ($carrito[$producto->id_producto] ?? 0) + $cantidad;

        if ($nueva > $producto->stock) {
            $carrito[$producto->id_producto] = $producto->stock;
            session(['carrito' => $carrito]);

            return back()->with('aviso', "Solo hay {$producto->stock} pieza(s) de {$producto->nombre_producto}; ajustamos tu carrito.");
        }

        $carrito[$producto->id_producto] = $nueva;
        session(['carrito' => $carrito]);

        return back()->with('exito', "Agregaste {$producto->nombre_producto} al carrito.");
    }

    public function actualizar(Request $request, Producto $producto)
    {
        $carrito = session('carrito', []);

        if (! isset($carrito[$producto->id_producto])) {
            return back();
        }

        $cantidad = min((int) $request->input('cantidad', 1), (int) $producto->stock);

        if ($cantidad <= 0) {
            unset($carrito[$producto->id_producto]);
        } else {
            $carrito[$producto->id_producto] = $cantidad;
        }

        session(['carrito' => $carrito]);

        return back();
    }

    public function quitar(Producto $producto)
    {
        $carrito = session('carrito', []);
        unset($carrito[$producto->id_producto]);
        session(['carrito' => $carrito]);

        return back()->with('exito', 'Quitaste el producto del carrito.');
    }

    public function vaciar()
    {
        session()->forget('carrito');

        return back()->with('exito', 'Vaciaste tu carrito.');
    }

    /**
     * Convierte lo que hay en sesión (id => cantidad) en líneas con datos
     * frescos de la base de datos (precio y stock actuales).
     */
    private function lineas(): array
    {
        $carrito = session('carrito', []);

        if (empty($carrito)) {
            return [];
        }

        $productos = Producto::with('categoria')
            ->whereIn('id_producto', array_keys($carrito))
            ->get()
            ->keyBy('id_producto');

        $lineas = [];

        foreach ($carrito as $id => $cantidad) {
            $producto = $productos->get($id);

            if (! $producto || ! $producto->estado) {
                continue;
            }

            $lineas[] = [
                'producto' => $producto,
                'cantidad' => $cantidad,
                'subtotal' => round((float) $producto->precio * $cantidad, 2),
                'disponible' => $producto->stock >= $cantidad,
            ];
        }

        return $lineas;
    }
}
