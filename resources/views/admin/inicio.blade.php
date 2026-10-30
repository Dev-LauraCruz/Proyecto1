@extends('layouts.app')

@section('titulo', 'Panel de administración')

@section('contenido')
    <h1>Panel de administración</h1>
    <p class="sub">Resumen general de la tienda.</p>

    <div class="tarjetas">
        <div class="tarjeta"><small>Clientes</small><strong>{{ $totalClientes }}</strong></div>
        <div class="tarjeta"><small>Productos</small><strong>{{ $totalProductos }}</strong></div>
        <div class="tarjeta"><small>Pedidos</small><strong>{{ $totalPedidos }}</strong></div>
        <div class="tarjeta"><small>Ventas (sin cancelados)</small><strong>${{ number_format($ventas, 2) }}</strong></div>
    </div>

    <h2>Últimos pedidos</h2>
    <div class="tabla">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th class="der">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ultimosPedidos as $pedido)
                    <tr>
                        <td>{{ $pedido->id_pedido }}</td>
                        <td>{{ $pedido->cliente?->nombreCompleto() ?? '—' }}</td>
                        <td>{{ $pedido->fecha_pedido?->format('d/m/Y') }}</td>
                        <td><span class="estado estado-{{ \Illuminate\Support\Str::slug($pedido->estado) }}">{{ $pedido->estado }}</span></td>
                        <td class="der">${{ number_format($pedido->total, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="vacio">Todavía no hay pedidos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
