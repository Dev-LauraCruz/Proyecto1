@extends('layouts.app')

@section('titulo', 'Comparar productos')

@section('contenido')
    @php
        $iconos = ['Laptops' => '💻', 'Smartphones' => '📱', 'Audio' => '🎧', 'Periféricos' => '⌨️', 'Almacenamiento' => '💾'];
    @endphp

    <h1>Comparar productos</h1>
    <p class="sub">Puedes comparar hasta 3 productos lado a lado.</p>

    @if ($productos->isEmpty())
        <div class="panel vacio">
            Todavía no elegiste productos para comparar.
            <a href="{{ route('cliente.inicio') }}">Ir a la tienda</a> y usa el botón «Comparar».
        </div>
    @else
        @if ($productos->count() === 1)
            <div class="aviso aviso-warn">Agrega al menos un producto más desde la tienda para poder compararlos.</div>
        @endif

        <div class="tabla comparar">
            <table>
                <thead>
                    <tr>
                        <th class="etiqueta"></th>
                        @foreach ($productos as $p)
                            <th style="text-transform: none; font-size: 1rem; color: var(--texto)">
                                {{ $iconos[$p->categoria?->nombre_categoria ?? ''] ?? '🛒' }} {{ $p->nombre_producto }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="etiqueta">Marca</th>
                        @foreach ($productos as $p)
                            <td>{{ $p->marca }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="etiqueta">Categoría</th>
                        @foreach ($productos as $p)
                            <td>{{ $p->categoria?->nombre_categoria }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="etiqueta">Precio</th>
                        @foreach ($productos as $p)
                            @php $esMejor = $productos->count() > 1 && (float) $p->precio === (float) $menor; @endphp
                            <td class="{{ $esMejor ? 'mejor' : '' }}">
                                <strong>${{ number_format($p->precio, 2) }}</strong>
                                @if ($esMejor)
                                    <br><small>Mejor precio</small>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="etiqueta">Disponibilidad</th>
                        @foreach ($productos as $p)
                            <td>
                                @if ($p->stock <= 0)
                                    <span class="stock-no">Agotado</span>
                                @elseif ($p->stock <= 5)
                                    <span class="stock-poco">Últimas {{ $p->stock }} piezas</span>
                                @else
                                    <span class="stock-ok">{{ $p->stock }} disponibles</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="etiqueta">Características</th>
                        @foreach ($productos as $p)
                            <td style="line-height: 1.45">{{ $p->descripcion }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="etiqueta"></th>
                        @foreach ($productos as $p)
                            <td>
                                <div class="acciones">
                                    <form method="POST" action="{{ route('cliente.carrito.agregar', $p->id_producto) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-chico" @disabled($p->stock <= 0)>Agregar al carrito</button>
                                    </form>
                                    <form method="POST" action="{{ route('cliente.comparar.alternar', $p->id_producto) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-peligro btn-chico">Quitar</button>
                                    </form>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="barra" style="margin-top: 1rem">
            <a class="btn btn-sec" href="{{ route('cliente.inicio') }}">Agregar más productos</a>
            <form method="POST" action="{{ route('cliente.comparar.vaciar') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-peligro">Vaciar comparación</button>
            </form>
        </div>
    @endif
@endsection
