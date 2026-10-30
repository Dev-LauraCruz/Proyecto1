@extends('layouts.app')

@section('titulo', 'Mis pedidos')

@section('contenido')
    <h1>Mis pedidos</h1>
    <p class="sub">Historial de tus compras. Puedes descargar el comprobante en PDF de cada una.</p>

    <div class="tabla">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Productos</th>
                    <th>Estado</th>
                    <th class="der">Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pedidos as $pedido)
                    <tr>
                        <td>{{ $pedido->id_pedido }}</td>
                        <td>{{ $pedido->fecha_pedido?->format('d/m/Y') }}</td>
                        <td>{{ $pedido->detalles_count }}</td>
                        <td><span class="estado estado-{{ \Illuminate\Support\Str::slug($pedido->estado) }}">{{ $pedido->estado }}</span></td>
                        <td class="der">${{ number_format($pedido->total, 2) }}</td>
                        <td class="der">
                            <div class="acciones" style="justify-content: flex-end">
                                <a class="btn btn-sec btn-chico" href="{{ route('cliente.pedidos.show', $pedido->id_pedido) }}">Ver</a>
                                <a class="btn btn-chico" href="{{ route('cliente.pedidos.pdf', $pedido->id_pedido) }}">PDF</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="vacio">Aún no tienes pedidos. <a href="{{ route('cliente.inicio') }}">Ir a la tienda</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
