@extends('layouts.app')

@section('titulo', 'Pedido #'.$pedido->id_pedido)

@section('contenido')
    @php
        $pago = $pedido->pagos->first();
        $d = $pedido->direccion;
    @endphp

    <h1>Pedido #{{ $pedido->id_pedido }}</h1>
    <p class="sub">
        {{ $pedido->fecha_pedido?->format('d/m/Y H:i') }} ·
        <span class="estado estado-{{ \Illuminate\Support\Str::slug($pedido->estado) }}">{{ $pedido->estado }}</span>
    </p>

    <div class="barra">
        <a class="btn" href="{{ route('cliente.pedidos.pdf', $pedido->id_pedido) }}">Descargar comprobante PDF</a>
        <a class="btn btn-sec" href="{{ route('cliente.pedidos') }}">Mis pedidos</a>
        <a class="btn btn-sec" href="{{ route('cliente.inicio') }}">Seguir comprando</a>
    </div>

    <div class="tabla">
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Marca</th>
                    <th class="der">Cantidad</th>
                    <th class="der">Precio unitario</th>
                    <th class="der">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedido->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->producto?->nombre_producto ?? 'Producto eliminado' }}</td>
                        <td>{{ $detalle->producto?->marca }}</td>
                        <td class="der">{{ $detalle->cantidad }}</td>
                        <td class="der">${{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td class="der">${{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" class="der"><strong>Total</strong></td>
                    <td class="der"><strong>${{ number_format($pedido->total, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="tarjetas" style="margin-top: 1.5rem">
        <div class="panel">
            <h2>Envío</h2>
            @if ($d)
                <p>
                    {{ $d->calle }} {{ $d->numero_exterior }}@if ($d->numero_interior), int. {{ $d->numero_interior }}@endif<br>
                    {{ $d->colonia }}, {{ $d->ciudad }}, {{ $d->estado }}<br>
                    CP {{ $d->codigo_postal }}
                </p>
            @else
                <p class="sub">Sin dirección registrada.</p>
            @endif
        </div>
        <div class="panel">
            <h2>Pago</h2>
            @if ($pago)
                <p>
                    {{ $pago->metodo_pago }}<br>
                    Estado: <strong>{{ $pago->estado_pago }}</strong><br>
                    Referencia: {{ $pago->referencia }}
                </p>
            @else
                <p class="sub">Sin pago registrado.</p>
            @endif
        </div>
    </div>
@endsection
