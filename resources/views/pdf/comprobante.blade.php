<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante del pedido #{{ $pedido->id_pedido }}</title>
    <style>
        @page { margin: 34px 38px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1c2233; }
        .logo { font-size: 22px; font-weight: bold; }
        .logo span { color: #4f46e5; }
        .muted { color: #6b7287; }
        table { width: 100%; border-collapse: collapse; }
        .cabecera td { vertical-align: top; }
        .cabecera .der { text-align: right; }
        h2 { font-size: 12px; margin: 18px 0 6px; text-transform: uppercase; color: #4f46e5; }
        .datos td { vertical-align: top; width: 50%; padding-right: 14px; line-height: 1.5; }
        .items th { background: #eef0fa; text-align: left; padding: 7px 8px; font-size: 10px; text-transform: uppercase; }
        .items td { padding: 7px 8px; border-bottom: 1px solid #dfe3ee; }
        .items .num { text-align: right; }
        .total td { font-size: 13px; font-weight: bold; border-bottom: 0; padding-top: 10px; }
        .pie { margin-top: 28px; text-align: center; color: #6b7287; font-size: 10px; }
        .linea { border-top: 2px solid #4f46e5; margin: 12px 0 4px; }
    </style>
</head>
<body>
    @php
        $pago = $pedido->pagos->first();
        $d = $pedido->direccion;
        $c = $pedido->cliente;
    @endphp

    <table class="cabecera">
        <tr>
            <td>
                <div class="logo">Tecno<span>Market</span></div>
                <div class="muted">Comprobante de compra</div>
            </td>
            <td class="der">
                <strong>Pedido #{{ $pedido->id_pedido }}</strong><br>
                {{ $pedido->fecha_pedido?->format('d/m/Y H:i') }}<br>
                Estado: {{ $pedido->estado }}
            </td>
        </tr>
    </table>
    <div class="linea"></div>

    <table class="datos">
        <tr>
            <td>
                <h2>Cliente</h2>
                {{ $c?->nombreCompleto() }}<br>
                {{ $c?->correo }}<br>
                {{ $c?->telefono }}
            </td>
            <td>
                <h2>Envío</h2>
                @if ($d)
                    {{ $d->calle }} {{ $d->numero_exterior }}@if ($d->numero_interior), int. {{ $d->numero_interior }}@endif<br>
                    {{ $d->colonia }}<br>
                    {{ $d->ciudad }}, {{ $d->estado }}. CP {{ $d->codigo_postal }}
                @else
                    Sin dirección registrada
                @endif
            </td>
        </tr>
    </table>

    <h2>Productos</h2>
    <table class="items">
        <thead>
            <tr>
                <th>Producto</th>
                <th class="num">Cant.</th>
                <th class="num">Precio unit.</th>
                <th class="num">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pedido->detalles as $detalle)
                <tr>
                    <td>
                        {{ $detalle->producto?->nombre_producto ?? 'Producto eliminado' }}<br>
                        <span class="muted">{{ $detalle->producto?->marca }}</span>
                    </td>
                    <td class="num">{{ $detalle->cantidad }}</td>
                    <td class="num">${{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td class="num">${{ number_format($detalle->subtotal, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td colspan="3" class="num">Total</td>
                <td class="num">${{ number_format($pedido->total, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <h2>Pago</h2>
    @if ($pago)
        {{ $pago->metodo_pago }} · {{ $pago->estado_pago }}<br>
        Referencia: {{ $pago->referencia }}
    @else
        Sin pago registrado
    @endif

    <div class="pie">
        Gracias por tu compra en TecnoMarket · Documento generado el {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
