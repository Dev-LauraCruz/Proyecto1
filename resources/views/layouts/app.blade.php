<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo') · TecnoMarket</title>
    <style>
        :root {
            --fondo: #f3f5fb;
            --tarjeta: #ffffff;
            --texto: #1c2233;
            --suave: #6b7287;
            --borde: #dfe3ee;
            --primario: #4f46e5;
            --ok: #16a34a;
            --warn: #d97706;
            --error: #dc2626;
        }
        @media (prefers-color-scheme: dark) {
            :root {
                --fondo: #0f1220;
                --tarjeta: #181c30;
                --texto: #eef0fa;
                --suave: #9aa1bd;
                --borde: #2b3150;
                --primario: #6d66f5;
                --ok: #34d399;
                --warn: #fbbf24;
                --error: #ff7a87;
            }
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: var(--fondo);
            color: var(--texto);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        a { color: var(--primario); }

        /* ---------- Encabezado y navegación ---------- */
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            padding: .8rem 1.5rem;
            background: var(--tarjeta);
            border-bottom: 1px solid var(--borde);
        }
        .logo { font-weight: 800; font-size: 1.25rem; letter-spacing: -.02em; }
        .logo span { color: var(--primario); }
        .nav { display: flex; gap: .25rem; flex-wrap: wrap; }
        .nav a {
            padding: .45rem .8rem; border-radius: 8px; text-decoration: none;
            color: var(--suave); font-size: .9rem; font-weight: 600;
        }
        .nav a:hover { color: var(--texto); }
        .nav a.activo { background: rgba(79, 70, 229, .14); color: var(--primario); }
        .nav .n {
            display: inline-block; min-width: 1.25rem; padding: 0 .35rem; margin-left: .3rem;
            border-radius: 999px; background: var(--primario); color: #fff;
            font-size: .7rem; text-align: center; line-height: 1.25rem;
        }
        .usuario { display: flex; align-items: center; gap: .75rem; font-size: .9rem; }
        .rol {
            font-size: .72rem; font-weight: 700; text-transform: uppercase;
            padding: .2rem .55rem; border-radius: 999px;
            background: rgba(79, 70, 229, .14); color: var(--primario);
        }
        main { max-width: 1100px; margin: 0 auto; padding: 2rem 1.5rem 3rem; }
        h1 { font-size: 1.6rem; margin-bottom: .3rem; }
        h2 { font-size: 1.1rem; margin: 1.5rem 0 .8rem; }
        .sub { color: var(--suave); margin-bottom: 1.5rem; }

        /* ---------- Avisos ---------- */
        .aviso { padding: .75rem 1rem; border-radius: 10px; font-size: .9rem; margin-bottom: 1rem; border: 1px solid; }
        .aviso-ok { background: rgba(22,163,74,.12); border-color: rgba(22,163,74,.4); color: var(--ok); }
        .aviso-warn { background: rgba(217,119,6,.12); border-color: rgba(217,119,6,.4); color: var(--warn); }
        .aviso-error { background: rgba(220,38,38,.1); border-color: rgba(220,38,38,.4); color: var(--error); }

        /* ---------- Botones y campos ---------- */
        .btn {
            display: inline-block; padding: .55rem 1rem; border-radius: 9px; cursor: pointer;
            border: 1px solid var(--primario); background: var(--primario); color: #fff;
            font-size: .88rem; font-weight: 600; text-decoration: none; font-family: inherit;
        }
        .btn:hover { filter: brightness(1.12); }
        .btn[disabled] { opacity: .5; cursor: not-allowed; filter: none; }
        .btn-sec { background: transparent; color: var(--primario); }
        .btn-peligro { background: transparent; border-color: var(--error); color: var(--error); }
        .btn-chico { padding: .35rem .7rem; font-size: .8rem; }
        .btn-block { width: 100%; text-align: center; padding: .8rem; font-size: 1rem; }
        input[type=text], input[type=search], input[type=number], input[type=date], select {
            padding: .55rem .8rem; border: 1px solid var(--borde); border-radius: 9px;
            background: var(--tarjeta); color: var(--texto); font-size: .9rem; font-family: inherit;
        }
        input:focus, select:focus { outline: none; border-color: var(--primario); }
        .filtros { display: flex; gap: .7rem; flex-wrap: wrap; align-items: flex-end; margin-bottom: 1.25rem; }
        .filtros .campo { display: flex; flex-direction: column; gap: .25rem; }
        .filtros label { font-size: .75rem; color: var(--suave); font-weight: 600; }

        /* ---------- Tarjetas y tablas ---------- */
        .tarjetas { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .tarjeta { background: var(--tarjeta); border: 1px solid var(--borde); border-radius: 14px; padding: 1.1rem 1.25rem; }
        .tarjeta small { color: var(--suave); font-size: .8rem; }
        .tarjeta strong { display: block; font-size: 1.6rem; margin-top: .2rem; }
        .panel { background: var(--tarjeta); border: 1px solid var(--borde); border-radius: 14px; padding: 1.2rem; }
        .panel h2 { margin-top: 0; }
        .tabla { background: var(--tarjeta); border: 1px solid var(--borde); border-radius: 14px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 560px; }
        th, td { padding: .75rem 1.1rem; text-align: left; font-size: .9rem; vertical-align: middle; }
        th { color: var(--suave); font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; border-bottom: 1px solid var(--borde); }
        tr + tr td, tr + tr th { border-top: 1px solid var(--borde); }
        .der { text-align: right; }
        .vacio { padding: 2rem; text-align: center; color: var(--suave); }
        .estado { font-size: .75rem; font-weight: 700; padding: .2rem .6rem; border-radius: 999px; background: rgba(120,120,140,.18); }
        .estado-entregado { background: rgba(22,163,74,.16); color: #16a34a; }
        .estado-enviado   { background: rgba(37,99,235,.16); color: #2563eb; }
        .estado-pagado    { background: rgba(8,145,178,.16); color: #0891b2; }
        .estado-pendiente { background: rgba(217,119,6,.18); color: #d97706; }
        .estado-cancelado { background: rgba(220,38,38,.16); color: #dc2626; }
        .acciones { display: flex; gap: .5rem; flex-wrap: wrap; }
        .acciones form { margin: 0; }
        .barra { display: flex; gap: .75rem; align-items: center; flex-wrap: wrap; margin-bottom: 1.25rem; }

        /* ---------- Tienda ---------- */
        .grid-productos { display: grid; grid-template-columns: repeat(auto-fill, minmax(235px, 1fr)); gap: 1rem; }
        .producto {
            background: var(--tarjeta); border: 1px solid var(--borde); border-radius: 14px;
            padding: 1rem; display: flex; flex-direction: column; gap: .45rem;
        }
        .producto.comparando { border-color: var(--primario); box-shadow: 0 0 0 2px rgba(79,70,229,.25); }
        .producto .foto {
            height: 110px; border-radius: 10px; display: grid; place-items: center; font-size: 3rem;
            background: linear-gradient(135deg, rgba(79,70,229,.14), rgba(6,182,212,.16));
        }
        .producto .marca { color: var(--suave); font-size: .78rem; }
        .producto h3 { font-size: 1rem; }
        .producto .desc { color: var(--suave); font-size: .84rem; line-height: 1.4; flex: 1; }
        .precio { font-size: 1.25rem; font-weight: 800; }
        .stock { font-size: .76rem; font-weight: 600; }
        .stock-ok { color: var(--ok); }
        .stock-poco { color: var(--warn); }
        .stock-no { color: var(--error); }

        /* ---------- Carrito ---------- */
        .carrito-grid { display: grid; grid-template-columns: 1fr 350px; gap: 1.25rem; align-items: start; }
        @media (max-width: 880px) { .carrito-grid { grid-template-columns: 1fr; } }
        .opcion {
            display: flex; gap: .6rem; align-items: flex-start; padding: .6rem .7rem;
            border: 1px solid var(--borde); border-radius: 10px; margin-bottom: .5rem;
            cursor: pointer; font-size: .85rem; line-height: 1.35;
        }
        .opcion input { margin-top: .2rem; }
        .fila-total { display: flex; justify-content: space-between; font-weight: 800; font-size: 1.15rem; margin: 1rem 0; }
        .cant { width: 4.2rem; }

        /* ---------- Comparador ---------- */
        .comparar table { min-width: 640px; }
        .comparar th.etiqueta { width: 130px; }
        .mejor { color: var(--ok); font-weight: 700; }
    </style>
</head>
<body>
    @php($u = auth()->user())
    <header>
        <div class="logo">Tecno<span>Market</span></div>

        <nav class="nav">
            @if ($u->esAdmin())
                <a href="{{ route('admin.inicio') }}" class="{{ request()->routeIs('admin.inicio') ? 'activo' : '' }}">Panel</a>
                <a href="{{ route('admin.reporte') }}" class="{{ request()->routeIs('admin.reporte*') ? 'activo' : '' }}">Reporte de ventas</a>
            @else
                <a href="{{ route('cliente.inicio') }}" class="{{ request()->routeIs('cliente.inicio') ? 'activo' : '' }}">Tienda</a>
                <a href="{{ route('cliente.comparar') }}" class="{{ request()->routeIs('cliente.comparar') ? 'activo' : '' }}">Comparar<span class="n">{{ count(session('comparar', [])) }}</span></a>
                <a href="{{ route('cliente.carrito') }}" class="{{ request()->routeIs('cliente.carrito') ? 'activo' : '' }}">Carrito<span class="n">{{ array_sum(session('carrito', [])) }}</span></a>
                <a href="{{ route('cliente.pedidos') }}" class="{{ request()->routeIs('cliente.pedidos*') ? 'activo' : '' }}">Mis pedidos</a>
            @endif
        </nav>

        <div class="usuario">
            <span>{{ $u->nombreCompleto() }}</span>
            <span class="rol">{{ $u->rol }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sec btn-chico">Cerrar sesión</button>
            </form>
        </div>
    </header>

    <main>
        @if (session('exito'))
            <div class="aviso aviso-ok">{{ session('exito') }}</div>
        @endif
        @if (session('aviso'))
            <div class="aviso aviso-warn">{{ session('aviso') }}</div>
        @endif
        @if ($errors->any())
            <div class="aviso aviso-error">
                @foreach ($errors->all() as $mensaje)
                    <div>{{ $mensaje }}</div>
                @endforeach
            </div>
        @endif

        @yield('contenido')
    </main>
</body>
</html>
