@extends('layouts.app')

@section('titulo', 'Reporte de ventas')

@section('contenido')
    <h1>Reporte de ventas</h1>
    <p class="sub">Lo que se ha vendido en la tienda, calculado en vivo desde la base de datos.</p>

    <form method="GET" action="{{ route('admin.reporte') }}" class="filtros">
        <div class="campo">
            <label for="desde">Desde</label>
            <input type="date" id="desde" name="desde" value="{{ $desde }}">
        </div>
        <div class="campo">
            <label for="hasta">Hasta</label>
            <input type="date" id="hasta" name="hasta" value="{{ $hasta }}">
        </div>
        <div class="campo">
            <label for="estado">Estado del pedido</label>
            <select id="estado" name="estado">
                <option value="">Todos (sin cancelados)</option>
                @foreach (['Pendiente', 'Pagado', 'Enviado', 'Entregado', 'Cancelado'] as $e)
                    <option value="{{ $e }}" @selected($estado === $e)>{{ $e }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn">Aplicar</button>
        <a class="btn btn-sec" href="{{ route('admin.reporte') }}">Limpiar</a>
        <a class="btn btn-sec" href="{{ route('admin.reporte.pdf', request()->query()) }}">Descargar PDF</a>
    </form>

    <div class="tarjetas">
        <div class="tarjeta"><small>Pedidos</small><strong>{{ $totales['pedidos'] }}</strong></div>
        <div class="tarjeta"><small>Unidades vendidas</small><strong>{{ $totales['unidades'] }}</strong></div>
        <div class="tarjeta"><small>Ingresos</small><strong>${{ number_format($totales['ingresos'], 2) }}</strong></div>
        <div class="tarjeta"><small>Ticket promedio</small><strong>${{ number_format($totales['ticket'], 2) }}</strong></div>
    </div>

    <h2>Ventas por categoría</h2>
    <div class="tabla">
        <table>
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th class="der">Unidades</th>
                    <th class="der">Ingresos</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($porCategoria as $fila)
                    <tr>
                        <td>{{ $fila->categoria ?? 'Sin categoría' }}</td>
                        <td class="der">{{ $fila->unidades }}</td>
                        <td class="der">${{ number_format($fila->ingresos, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="vacio">No hay ventas con estos filtros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h2>Productos vendidos</h2>
    <div class="tabla">
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Marca</th>
                    <th>Categoría</th>
                    <th class="der">Unidades</th>
                    <th class="der">Ingresos</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($porProducto as $fila)
                    <tr>
                        <td>{{ $fila->nombre_producto }}</td>
                        <td>{{ $fila->marca }}</td>
                        <td>{{ $fila->categoria ?? 'Sin categoría' }}</td>
                        <td class="der">{{ $fila->unidades }}</td>
                        <td class="der">${{ number_format($fila->ingresos, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="vacio">No hay ventas con estos filtros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h2>Pedidos incluidos</h2>
    <div class="tabla">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th class="der">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pedidos as $p)
                    <tr>
                        <td>{{ $p->id_pedido }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($p->fecha_pedido)->format('d/m/Y') }}</td>
                        <td>{{ $p->nombre }} {{ $p->apellido_p }}</td>
                        <td><span class="estado estado-{{ \Illuminate\Support\Str::slug($p->estado) }}">{{ $p->estado }}</span></td>
                        <td class="der">${{ number_format($p->total, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="vacio">No hay pedidos con estos filtros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
