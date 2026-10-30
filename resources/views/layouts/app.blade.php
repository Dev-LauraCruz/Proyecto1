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
        }
        @media (prefers-color-scheme: dark) {
            :root {
                --fondo: #0f1220;
                --tarjeta: #181c30;
                --texto: #eef0fa;
                --suave: #9aa1bd;
                --borde: #2b3150;
                --primario: #6d66f5;
            }
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: var(--fondo);
            color: var(--texto);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            padding: .9rem 1.5rem;
            background: var(--tarjeta);
            border-bottom: 1px solid var(--borde);
        }
        .logo { font-weight: 800; font-size: 1.25rem; letter-spacing: -.02em; }
        .logo span { color: var(--primario); }
        .usuario { display: flex; align-items: center; gap: .75rem; font-size: .9rem; }
        .rol {
            font-size: .72rem; font-weight: 700; text-transform: uppercase;
            padding: .2rem .55rem; border-radius: 999px;
            background: rgba(79, 70, 229, .14); color: var(--primario);
        }
        .usuario button {
            padding: .45rem .9rem; border-radius: 8px; cursor: pointer;
            border: 1px solid var(--borde); background: transparent; color: var(--texto);
            font-size: .85rem;
        }
        .usuario button:hover { border-color: var(--primario); color: var(--primario); }
        main { max-width: 1050px; margin: 0 auto; padding: 2rem 1.5rem 3rem; }
        h1 { font-size: 1.6rem; margin-bottom: .3rem; }
        .sub { color: var(--suave); margin-bottom: 1.75rem; }
        .tarjetas { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .tarjeta { background: var(--tarjeta); border: 1px solid var(--borde); border-radius: 14px; padding: 1.1rem 1.25rem; }
        .tarjeta small { color: var(--suave); font-size: .8rem; }
        .tarjeta strong { display: block; font-size: 1.7rem; margin-top: .2rem; }
        h2 { font-size: 1.1rem; margin-bottom: .8rem; }
        .tabla { background: var(--tarjeta); border: 1px solid var(--borde); border-radius: 14px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 560px; }
        th, td { padding: .8rem 1.1rem; text-align: left; font-size: .92rem; }
        th { color: var(--suave); font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; border-bottom: 1px solid var(--borde); }
        tr + tr td { border-top: 1px solid var(--borde); }
        .der { text-align: right; }
        .vacio { padding: 2rem; text-align: center; color: var(--suave); }
        .estado { font-size: .75rem; font-weight: 700; padding: .2rem .6rem; border-radius: 999px; background: rgba(120,120,140,.18); }
        .estado-entregado { background: rgba(22,163,74,.16); color: #16a34a; }
        .estado-enviado   { background: rgba(37,99,235,.16); color: #2563eb; }
        .estado-pagado    { background: rgba(8,145,178,.16); color: #0891b2; }
        .estado-pendiente { background: rgba(217,119,6,.18); color: #d97706; }
        .estado-cancelado { background: rgba(220,38,38,.16); color: #dc2626; }
    </style>
</head>
<body>
    <header>
        <div class="logo">Tecno<span>Market</span></div>
        <div class="usuario">
            <span>{{ auth()->user()->nombreCompleto() }}</span>
            <span class="rol">{{ auth()->user()->rol }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
        </div>
    </header>

    <main>
        @yield('contenido')
    </main>
</body>
</html>
