<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión · TecnoMarket</title>
    <style>
        :root {
            --fondo: #f3f5fb;
            --tarjeta: #ffffff;
            --texto: #1c2233;
            --suave: #6b7287;
            --borde: #dfe3ee;
            --primario: #4f46e5;
            --primario-osc: #3730a3;
            --error: #c62839;
        }
        @media (prefers-color-scheme: dark) {
            :root {
                --fondo: #0f1220;
                --tarjeta: #181c30;
                --texto: #eef0fa;
                --suave: #9aa1bd;
                --borde: #2b3150;
                --primario: #6d66f5;
                --primario-osc: #8b85ff;
                --error: #ff7a87;
            }
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 1.5rem;
            background: var(--fondo);
            color: var(--texto);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        .caja {
            width: 100%;
            max-width: 880px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: var(--tarjeta);
            border: 1px solid var(--borde);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(30, 30, 80, .12);
        }
        .marca {
            padding: 3rem 2.5rem;
            background: linear-gradient(145deg, #4f46e5 0%, #7c3aed 55%, #06b6d4 120%);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 2rem;
        }
        .logo { font-size: 1.5rem; font-weight: 800; letter-spacing: -.02em; }
        .logo span { opacity: .75; font-weight: 600; }
        .marca h2 { font-size: 1.9rem; line-height: 1.2; margin-bottom: .75rem; }
        .marca p { opacity: .85; line-height: 1.55; }
        .marca small { opacity: .7; }
        .formulario { padding: 3rem 2.5rem; }
        .formulario h1 { font-size: 1.6rem; margin-bottom: .35rem; }
        .formulario .sub { color: var(--suave); margin-bottom: 1.75rem; }
        label { display: block; font-size: .85rem; font-weight: 600; margin-bottom: .4rem; }
        input[type=email], input[type=password] {
            width: 100%;
            padding: .8rem 1rem;
            border: 1px solid var(--borde);
            border-radius: 10px;
            background: transparent;
            color: var(--texto);
            font-size: 1rem;
            margin-bottom: 1.1rem;
            transition: border-color .15s, box-shadow .15s;
        }
        input:focus {
            outline: none;
            border-color: var(--primario);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .2);
        }
        .error {
            background: rgba(198, 40, 57, .1);
            color: var(--error);
            border: 1px solid rgba(198, 40, 57, .35);
            padding: .7rem .9rem;
            border-radius: 10px;
            font-size: .9rem;
            margin-bottom: 1.1rem;
        }
        button {
            width: 100%;
            padding: .85rem;
            border: 0;
            border-radius: 10px;
            background: var(--primario);
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background .15s;
        }
        button:hover { background: var(--primario-osc); }
        .demo {
            margin-top: 1.5rem;
            padding: .9rem 1rem;
            border: 1px dashed var(--borde);
            border-radius: 10px;
            font-size: .82rem;
            color: var(--suave);
            line-height: 1.6;
        }
        .demo b { color: var(--texto); }
        @media (max-width: 720px) {
            .caja { grid-template-columns: 1fr; }
            .marca { padding: 2rem 1.75rem; }
            .marca h2 { font-size: 1.4rem; }
            .formulario { padding: 2rem 1.75rem; }
        }
    </style>
</head>
<body>
    <main class="caja">
        <section class="marca">
            <div class="logo">Tecno<span>Market</span></div>
            <div>
                <h2>Tu tienda de tecnología, en un solo lugar.</h2>
                <p>Inicia sesión para consultar tus pedidos o administrar el catálogo.</p>
            </div>
            <small>© {{ date('Y') }} TecnoMarket MX</small>
        </section>

        <section class="formulario">
            <h1>Iniciar sesión</h1>
            <p class="sub">Ingresa con tu correo y contraseña.</p>

            @if ($errors->any())
                <div class="error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" novalidate>
                @csrf

                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo" value="{{ old('correo') }}"
                       placeholder="tucorreo@ejemplo.com" autocomplete="email" autofocus required>

                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password"
                       placeholder="••••••••" autocomplete="current-password" required>

                <button type="submit">Entrar</button>
            </form>

            @env('local')
                <div class="demo">
                    <b>Cuentas de prueba</b> (solo se muestran en local)<br>
                    Admin: admin@tecnomarket.mx · admin123<br>
                    Cliente: carlos.hernandez@correo.com · carlos123
                </div>
            @endenv
        </section>
    </main>
</body>
</html>
