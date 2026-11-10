<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión — Área de Cliente</title>
    <link rel="stylesheet" href="{{ asset('css/cliente.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cliente-panel.css') }}">
</head>
<body>
    <main class="card ancho">
        <h1>Área de Cliente</h1>
        <p>Inicia sesión para consultar tu perfil y el catálogo de alimentos</p>

        <form id="form-login" class="formulario">
            <label>
                Correo
                <input type="email" name="correo" required maxlength="150" autocomplete="email">
            </label>
            <label>
                Contraseña
                <input type="password" name="password" required minlength="8" autocomplete="current-password">
            </label>
            <p id="error-login" class="error" hidden></p>
            <button type="submit" class="boton">Ingresar</button>
        </form>
    </main>

    <script>
        const formulario = document.getElementById('form-login');
        const errorLogin = document.getElementById('error-login');

        formulario.addEventListener('submit', async (evento) => {
            evento.preventDefault();
            errorLogin.hidden = true;

            const datos = Object.fromEntries(new FormData(formulario));

            const respuesta = await fetch('/api/v1/cliente/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(datos),
            });

            const cuerpo = await respuesta.json().catch(() => ({}));

            if (respuesta.ok) {
                localStorage.setItem('cliente_token', cuerpo.token);
                localStorage.setItem('cliente_usuario', JSON.stringify(cuerpo.usuario));
                window.location.href = '/cliente/panel';
                return;
            }

            errorLogin.hidden = false;
            if (cuerpo.errors) {
                errorLogin.textContent = Object.values(cuerpo.errors).flat().join(' ');
            } else {
                errorLogin.textContent = cuerpo.message || 'No se pudo iniciar sesión';
            }
        });
    </script>
</body>
</html>
