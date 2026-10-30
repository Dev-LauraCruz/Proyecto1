@extends('layouts.app')

@section('titulo', 'Tienda')

@section('contenido')
    @php
        $iconos = ['Laptops' => '💻', 'Smartphones' => '📱', 'Audio' => '🎧', 'Periféricos' => '⌨️', 'Almacenamiento' => '💾'];
    @endphp

    <h1>Tienda</h1>
    <p class="sub">Hola, {{ auth()->user()->nombre }}. Elige tus productos, compáralos y agrégalos al carrito.</p>

    <form method="GET" action="{{ route('cliente.inicio') }}" class="filtros">
        <div class="campo">
            <label for="q">Buscar</label>
            <input type="search" id="q" name="q" value="{{ request('q') }}" placeholder="Nombre o marca">
        </div>
        <div class="campo">
            <label for="categoria">Categoría</label>
            <select id="categoria" name="categoria">
                <option value="">Todas</option>
                @foreach ($categorias as $c)
                    <option value="{{ $c->id_categoria }}" @selected(request('categoria') == $c->id_categoria)>{{ $c->nombre_categoria }}</option>
                @endforeach
            </select>
        </div>
        <div class="campo">
            <label for="orden">Ordenar por</label>
            <select id="orden" name="orden">
                <option value="nombre" @selected(request('orden', 'nombre') === 'nombre')>Nombre</option>
                <option value="precio_asc" @selected(request('orden') === 'precio_asc')>Precio: menor a mayor</option>
                <option value="precio_desc" @selected(request('orden') === 'precio_desc')>Precio: mayor a menor</option>
            </select>
        </div>
        <button class="btn" type="submit">Filtrar</button>
        @if (request()->hasAny(['q', 'categoria', 'orden']))
            <a class="btn btn-sec" href="{{ route('cliente.inicio') }}">Limpiar</a>
        @endif
    </form>

    @if (count($enComparacion) > 0)
        <div class="barra">
            <span>Comparando <strong>{{ count($enComparacion) }}</strong> producto(s).</span>
            <a class="btn btn-sec btn-chico" href="{{ route('cliente.comparar') }}">Ver comparación</a>
        </div>
    @endif

    <p class="sub">{{ $productos->count() }} producto(s) disponible(s)</p>

    @if ($productos->isEmpty())
        <div class="panel vacio">No encontramos productos con esos filtros.</div>
    @else
        <div class="grid-productos">
            @foreach ($productos as $p)
                @php $comparando = in_array($p->id_producto, $enComparacion); @endphp
                <article class="producto {{ $comparando ? 'comparando' : '' }}">
                    <div class="foto">{{ $iconos[$p->categoria?->nombre_categoria ?? ''] ?? '🛒' }}</div>
                    <small class="marca">{{ $p->marca }} · {{ $p->categoria?->nombre_categoria }}</small>
                    <h3>{{ $p->nombre_producto }}</h3>
                    <p class="desc">{{ \Illuminate\Support\Str::limit($p->descripcion, 95) }}</p>
                    <div class="precio">${{ number_format($p->precio, 2) }}</div>

                    @if ($p->stock <= 0)
                        <div class="stock stock-no">Agotado</div>
                    @elseif ($p->stock <= 5)
                        <div class="stock stock-poco">¡Últimas {{ $p->stock }} piezas!</div>
                    @else
                        <div class="stock stock-ok">Disponible ({{ $p->stock }})</div>
                    @endif

                    <div class="acciones">
                        <form method="POST" action="{{ route('cliente.carrito.agregar', $p->id_producto) }}">
                            @csrf
                            <button type="submit" class="btn btn-chico" @disabled($p->stock <= 0)>Agregar al carrito</button>
                        </form>
                        <form method="POST" action="{{ route('cliente.comparar.alternar', $p->id_producto) }}">
                            @csrf
                            <button type="submit" class="btn btn-sec btn-chico">{{ $comparando ? 'Quitar de comparar' : 'Comparar' }}</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
@endsection
