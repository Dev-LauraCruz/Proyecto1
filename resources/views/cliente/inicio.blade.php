@extends('layouts.app')

@section('titulo', 'Mi cuenta')

@section('contenido')
    <h1>Hola, {{ auth()->user()->nombre }} 👋</h1>
    <p class="sub">Aquí puedes ver el historial de tus pedidos.</p>

    <h2>Mis pedidos</h2>
    <div class="tabla">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Productos</th>
                    <th>Estado</th>
                    <th class="der">Total</th>
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
                    </tr>
                @empty
                    <tr><td colspan="5" class="vacio">Aún no tienes pedidos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
