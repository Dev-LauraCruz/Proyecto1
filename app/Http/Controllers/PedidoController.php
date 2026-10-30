<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\MovimientoInventario;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Producto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RuntimeException;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $pedidos = $request->user()
            ->pedidos()
            ->withCount('detalles')
            ->orderByDesc('fecha_pedido')
            ->get();

        return view('cliente.pedidos', compact('pedidos'));
    }

    public function show(Request $request, int $id)
    {
        $pedido = $this->delCliente($request, $id);

        return view('cliente.pedido', compact('pedido'));
    }

    public function pdf(Request $request, int $id)
    {
        $pedido = $this->delCliente($request, $id);

        return Pdf::loadView('pdf.comprobante', compact('pedido'))
            ->setPaper('letter')
            ->download("comprobante-pedido-{$pedido->id_pedido}.pdf");
    }

    public function store(Request $request)
    {
        $cliente = $request->user();

        $datos = $request->validate([
            'id_direccion' => ['required', 'integer'],
            'metodo_pago' => ['required', Rule::in(['Tarjeta', 'Transferencia', 'Efectivo'])],
        ], [
            'id_direccion.required' => 'Elige una dirección de envío.',
            'metodo_pago.required' => 'Elige un método de pago.',
            'metodo_pago.in' => 'Elige un método de pago válido.',
        ]);

        // La dirección debe ser de este cliente
        $direccion = $cliente->direcciones()
            ->where('id_direccion', $datos['id_direccion'])
            ->first();

        if (! $direccion) {
            return back()->withErrors(['id_direccion' => 'Elige una de tus direcciones de envío.'])->withInput();
        }

        $carrito = session('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('cliente.carrito')->withErrors(['carrito' => 'Tu carrito está vacío.']);
        }

        try {
            $pedido = DB::transaction(function () use ($cliente, $direccion, $carrito, $datos) {
                // Se bloquean los productos para que nadie más compre el mismo stock a la vez
                $productos = Producto::whereIn('id_producto', array_keys($carrito))
                    ->where('estado', 1)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id_producto');

                $lineas = [];
                $total = 0;

                foreach ($carrito as $id => $cantidad) {
                    $producto = $productos->get($id);

                    if (! $producto) {
                        throw new RuntimeException('Uno de los productos de tu carrito ya no está disponible.');
                    }

                    if ($producto->stock < $cantidad) {
                        throw new RuntimeException("No hay suficiente stock de {$producto->nombre_producto} (disponibles: {$producto->stock}).");
                    }

                    $precio = (float) $producto->precio;
                    $subtotal = round($precio * $cantidad, 2);
                    $total += $subtotal;

                    $lineas[] = [
                        'producto' => $producto,
                        'cantidad' => $cantidad,
                        'precio' => $precio,
                        'subtotal' => $subtotal,
                    ];
                }

                // Con tarjeta el pago se aprueba al momento; con los otros métodos queda pendiente
                $pagadoAhora = $datos['metodo_pago'] === 'Tarjeta';

                $pedido = Pedido::create([
                    'id_cliente' => $cliente->id_cliente,
                    'id_direccion' => $direccion->id_direccion,
                    'fecha_pedido' => now(),
                    'total' => $total,
                    'estado' => $pagadoAhora ? 'Pagado' : 'Pendiente',
                ]);

                foreach ($lineas as $linea) {
                    DetallePedido::create([
                        'id_pedido' => $pedido->id_pedido,
                        'id_producto' => $linea['producto']->id_producto,
                        'cantidad' => $linea['cantidad'],
                        'precio_unitario' => $linea['precio'],
                        'subtotal' => $linea['subtotal'],
                    ]);

                    $linea['producto']->decrement('stock', $linea['cantidad']);

                    MovimientoInventario::create([
                        'id_producto' => $linea['producto']->id_producto,
                        'id_proveedor' => null,
                        'id_pedido' => $pedido->id_pedido,
                        'tipo_movimiento' => 'Salida',
                        'cantidad' => $linea['cantidad'],
                        'fecha_movimiento' => now(),
                        'motivo' => "Venta del pedido #{$pedido->id_pedido}",
                    ]);
                }

                Pago::create([
                    'id_pedido' => $pedido->id_pedido,
                    'fecha_pago' => $pagadoAhora ? now() : null,
                    'metodo_pago' => $datos['metodo_pago'],
                    'monto' => $total,
                    'estado_pago' => $pagadoAhora ? 'Aprobado' : 'Pendiente',
                    'referencia' => 'REF-'.strtoupper(Str::random(8)),
                ]);

                return $pedido;
            });
        } catch (RuntimeException $e) {
            return redirect()->route('cliente.carrito')->withErrors(['carrito' => $e->getMessage()]);
        }

        session()->forget('carrito');

        return redirect()
            ->route('cliente.pedidos.show', $pedido->id_pedido)
            ->with('exito', '¡Compra realizada! Aquí está tu comprobante.');
    }

    /**
     * Solo devuelve el pedido si pertenece al cliente que inició sesión.
     */
    private function delCliente(Request $request, int $id): Pedido
    {
        return Pedido::with(['cliente', 'direccion', 'detalles.producto', 'pagos'])
            ->where('id_cliente', $request->user()->id_cliente)
            ->where('id_pedido', $id)
            ->firstOrFail();
    }
}
