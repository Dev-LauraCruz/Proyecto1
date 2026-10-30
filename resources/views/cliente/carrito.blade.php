@extends('layouts.app')

@section('titulo', 'Mi carrito')

@section('contenido')
    <h1>Mi carrito</h1>
    <p class="sub">Revisa tus productos y confirma la compra.</p>

    @if (count($items) === 0)
        <div class="panel vacio">
            Tu carrito está vacío.
            <a href="{{ route('cliente.inicio') }}">Ir a la tienda</a>
        </div>
    @else
        @php $hayProblemas = collect($items)->contains(fn ($i) => ! $i['disponible']); @endphp

        <div class="carrito-grid">
            <div>
                <div class="tabla">
                    <table>
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th class="der">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $i)
                                @php $p = $i['producto']; @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $p->nombre_producto }}</strong><br>
                                        <small style="color: var(--suave)">{{ $p->marca }}</small>
                                        @unless ($i['disponible'])
                                            <br><small class="stock-no">Solo quedan {{ $p->stock }} pieza(s). Ajusta la cantidad.</small>
                                        @endunless
                                    </td>
                                    <td>${{ number_format($p->precio, 2) }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('cliente.carrito.actualizar', $p->id_producto) }}" class="acciones">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="cantidad" class="cant" min="0" max="{{ $p->stock }}" value="{{ $i['cantidad'] }}">
                                            <button type="submit" class="btn btn-sec btn-chico">Actualizar</button>
                                        </form>
                                    </td>
                                    <td class="der">${{ number_format($i['subtotal'], 2) }}</td>
                                    <td class="der">
                                        <form method="POST" action="{{ route('cliente.carrito.quitar', $p->id_producto) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-peligro btn-chico">Quitar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="barra" style="margin-top: 1rem">
                    <a class="btn btn-sec" href="{{ route('cliente.inicio') }}">Seguir comprando</a>
                    <form method="POST" action="{{ route('cliente.carrito.vaciar') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-peligro">Vaciar carrito</button>
                    </form>
                </div>
            </div>

            <aside class="panel">
                <form method="POST" action="{{ route('cliente.pedidos.store') }}">
                    @csrf

                    <h2>Dirección de envío</h2>
                    @forelse ($direcciones as $d)
                        <label class="opcion">
                            <input type="radio" name="id_direccion" value="{{ $d->id_direccion }}" @checked(old('id_direccion', $direcciones->first()->id_direccion) == $d->id_direccion)>
                            <span>
                                {{ $d->calle }} {{ $d->numero_exterior }}@if ($d->numero_interior), int. {{ $d->numero_interior }}@endif,
                                {{ $d->colonia }}, {{ $d->ciudad }}, {{ $d->estado }}. CP {{ $d->codigo_postal }}
                            </span>
                        </label>
                    @empty
                        <p class="sub">No tienes direcciones registradas, por eso no puedes confirmar la compra.</p>
                    @endforelse

                    <h2>Método de pago</h2>
                    @foreach (['Tarjeta' => 'Tarjeta de crédito o débito', 'Transferencia' => 'Transferencia bancaria', 'Efectivo' => 'Pago en efectivo'] as $valor => $texto)
                        <label class="opcion">
                            <input type="radio" name="metodo_pago" value="{{ $valor }}" @checked(old('metodo_pago', 'Tarjeta') === $valor)>
                            <span>{{ $texto }}</span>
                        </label>
                    @endforeach
                    <small style="color: var(--suave)">Simulación: no se realiza ningún cobro real. Con tarjeta el pedido queda pagado; con los otros métodos queda pendiente.</small>

                    <div class="fila-total">
                        <span>Total</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>

                    <button type="submit" class="btn btn-block" @disabled($hayProblemas || $direcciones->isEmpty())>Confirmar compra</button>
                </form>
            </aside>
        </div>
    @endif
@endsection
