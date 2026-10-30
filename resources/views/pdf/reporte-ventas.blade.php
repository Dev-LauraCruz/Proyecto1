<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de ventas</title>
    <style>
        @page { margin: 34px 38px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10.5px; color: #1c2233; }
        .logo { font-size: 22px; font-weight: bold; }
        .logo span { color: #4f46e5; }
        .muted { color: #6b7287; }
        table { width: 100%; border-collapse: collapse; }
        h2 { font-size: 12px; margin: 20px 0 6px; text-transform: uppercase; color: #4f46e5; }
        .linea { border-top: 2px solid #4f46e5; margin: 12px 0 8px; }
        .resumen td { width: 25%; padding: 10px; border: 1px solid #dfe3ee; vertical-align: top; }
        .resumen strong { display: block; font-size: 16px; margin-top: 3px; }
        .tabla th { background: #eef0fa; text-align: left; padding: 6px 8px; font-size: 9.5px; text-transform: uppercase; }
        .tabla td { padding: 6px 8px; border-bottom: 1px solid #dfe3ee; }
        .num { text-align: right; }
        .pie { margin-top: 24px; text-align: center; color: #6b7287; font-size: 9.5px; }
    </style>
</head>
<body>
    <div class="logo">Tecno<span>Market</span></div>
    <div class="muted">Reporte de ventas</div>
    <div class="linea"></div>

    <p>
        <strong>Periodo:</strong>
        @if ($desde || $hasta)
            {{ $desde ? \Illuminate\Support\Carbon::parse($desde)->format('d/m/Y') : 'inicio' }}
            al
            {{ $hasta ? \Illuminate\Support\Carbon::parse($hasta)->format('d/m/Y') : 'hoy' }}
        @else
            Todo el historial
        @endif
        &nbsp;·&nbsp;
        <strong>Estado:</strong> {{ $estado ?: 'Todos (sin cancelados)' }}
    </p>

    <table class="resumen" style="margin-top: 8px">
        <tr>
            <td><span class="muted">Pedidos</span><strong>{{ $totales['pedidos'] }}</strong></td>
            <td><span class="muted">Unidades vendidas</span><strong>{{ $totales['unidades'] }}</strong></td>
            <td><span class="muted">Ingresos</span><strong>${{ number_format($totales['ingresos'], 2) }}</strong></td>
            <td><span class="muted">Ticket promedio</span><strong>${{ number_format($totales['ticket'], 2) }}</strong></td>
        </tr>
    </table>

    <h2>Ventas por categoría</h2>
    <table class="tabla">
        <thead>
            <tr>
                <th>Categoría</th>
                <th class="num">Unidades</th>
                <th class="num">Ingresos</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($porCategoria as $fila)
                <tr>
                    <td>{{ $fila->categoria ?? 'Sin categoría' }}</td>
                    <td class="num">{{ $fila->unidades }}</td>
                    <td class="num">${{ number_format($fila->ingresos, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="muted">No hay ventas con estos filtros.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Productos vendidos</h2>
    <table class="tabla">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Marca</th>
                <th>Categoría</th>
                <th class="num">Unidades</th>
                <th class="num">Ingresos</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($porProducto as $fila)
                <tr>
                    <td>{{ $fila->nombre_producto }}</td>
                    <td>{{ $fila->marca }}</td>
                    <td>{{ $fila->categoria ?? 'Sin categoría' }}</td>
                    <td class="num">{{ $fila->unidades }}</td>
                    <td class="num">${{ number_format($fila->ingresos, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="muted">No hay ventas con estos filtros.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Pedidos incluidos</h2>
    <table class="tabla">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th class="num">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pedidos as $p)
                <tr>
                    <td>{{ $p->id_pedido }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($p->fecha_pedido)->format('d/m/Y') }}</td>
                    <td>{{ $p->nombre }} {{ $p->apellido_p }}</td>
                    <td>{{ $p->estado }}</td>
                    <td class="num">${{ number_format($p->total, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="muted">No hay pedidos con estos filtros.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="pie">Generado el {{ $generado->format('d/m/Y H:i') }} · TecnoMarket MX</div>
</body>
</html>
