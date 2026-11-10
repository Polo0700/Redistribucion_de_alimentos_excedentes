<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Cliente</title>
    <link rel="stylesheet" href="{{ asset('css/cliente.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cliente-panel.css') }}">
</head>
<body>
    <main class="card ancho">
        <h1>Panel de Cliente</h1>
        <p id="saludo">Cargando...</p>

        <nav class="navegacion">
            <button id="btn-alimentos" class="boton-menu activo" type="button">Catálogo</button>
            <button id="btn-pedidos" class="boton-menu" type="button">Mis pedidos</button>
            <button id="btn-donaciones" class="boton-menu" type="button">Mis donaciones</button>
            <button id="btn-perfil" class="boton-menu" type="button">Mi perfil</button>
            <button id="btn-salir" class="boton-menu salir" type="button">Cerrar sesión</button>
        </nav>

        <section id="vista-alimentos">
            <div id="lista-alimentos" class="lista-alimentos"></div>
        </section>

        <section id="vista-formulario" hidden>
            <h2 id="titulo-formulario" class="titulo-seccion"></h2>
            <p id="alimento-formulario"></p>

            <form id="form-pedir" class="formulario" hidden>
                <label>
                    Cantidad
                    <input type="number" name="cantidad" required min="0.01" step="0.01" value="1">
                </label>
                <label>
                    Dirección de entrega
                    <input name="direccion_entrega" required maxlength="200">
                </label>
                <label>
                    Observaciones
                    <input name="observaciones" maxlength="250" placeholder="Opcional">
                </label>
                <p id="mensaje-formulario" class="mensaje" hidden></p>
                <div class="acciones-formulario">
                    <button type="submit" class="boton">Confirmar pedido</button>
                    <button type="button" class="boton-menu" id="btn-volver">Volver al catálogo</button>
                </div>
            </form>

            <form id="form-donar" class="formulario" hidden>
                <label>
                    Cantidad
                    <input type="number" name="cantidad" required min="0.01" step="0.01" value="1">
                </label>
                <label>
                    Fecha límite
                    <input type="date" name="fecha_limite" required>
                </label>
                <label>
                    Ubicación
                    <input name="ubicacion" required maxlength="200" placeholder="Donde se entrega la donación">
                </label>
                <label>
                    Observaciones
                    <input name="observaciones" required maxlength="200" placeholder="Descripción de la donación">
                </label>
                <p id="mensaje-formulario-donacion" class="mensaje" hidden></p>
                <div class="acciones-formulario">
                    <button type="submit" class="boton">Confirmar donación</button>
                    <button type="button" class="boton-menu" id="btn-volver-donacion">Volver al catálogo</button>
                </div>
            </form>
        </section>

        <section id="vista-pedidos" hidden>
            <div id="lista-pedidos" class="lista-alimentos"></div>
        </section>

        <section id="vista-donaciones" hidden>
            <div id="lista-donaciones" class="lista-alimentos"></div>
        </section>

        <section id="vista-perfil" hidden>
            <form id="form-perfil" class="formulario">
                <label>
                    Nombre
                    <input name="nombre" required maxlength="100">
                </label>
                <label>
                    Apellido
                    <input name="apellido" required maxlength="100">
                </label>
                <label>
                    Teléfono
                    <input name="telefono" required maxlength="100">
                </label>
                <label>
                    Dirección
                    <input name="direccion" required maxlength="200">
                </label>
                <p id="mensaje-perfil" class="mensaje" hidden></p>
                <button type="submit" class="boton">Guardar cambios</button>
            </form>
        </section>
    </main>

    <script>
        const token = localStorage.getItem('cliente_token');

        if (!token) {
            window.location.href = '/cliente';
        }

        const cabecerasBase = {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token,
        };

        const vistas = ['alimentos', 'formulario', 'pedidos', 'donaciones', 'perfil'];
        let alimentoActual = null;

        async function pedir(url, opciones = {}) {
            const respuesta = await fetch(url, {
                ...opciones,
                headers: { ...cabecerasBase, ...(opciones.headers || {}) },
            });

            if (respuesta.status === 401) {
                localStorage.clear();
                window.location.href = '/cliente';
                return null;
            }

            return respuesta;
        }

        function mostrarVista(vista) {
            vistas.forEach((nombre) => {
                const seccion = document.getElementById('vista-' + nombre);
                if (seccion) seccion.hidden = nombre !== vista;
            });

            ['alimentos', 'pedidos', 'donaciones', 'perfil'].forEach((nombre) => {
                document.getElementById('btn-' + nombre).classList.toggle('activo', nombre === vista);
            });

            if (vista === 'alimentos') cargarAlimentos();
            if (vista === 'pedidos') cargarPedidos();
            if (vista === 'donaciones') cargarDonaciones();
        }

        function mensajeError(cuerpo) {
            if (cuerpo.errors) {
                return Object.values(cuerpo.errors).flat().join(' ');
            }
            return cuerpo.message || 'No se pudo completar la operación';
        }

        async function cargarPerfil() {
            const respuesta = await pedir('/api/v1/cliente/perfil');
            if (!respuesta) return;

            const cuerpo = await respuesta.json();
            const usuario = cuerpo.usuario;

            document.getElementById('saludo').textContent =
                'Hola, ' + usuario.nombre + ' ' + usuario.apellido;

            const formulario = document.getElementById('form-perfil');
            formulario.nombre.value = usuario.nombre;
            formulario.apellido.value = usuario.apellido;
            formulario.telefono.value = usuario.telefono;
            formulario.direccion.value = usuario.direccion;
        }

        async function cargarAlimentos() {
            const respuesta = await pedir('/api/v1/cliente/alimentos');
            if (!respuesta) return;

            const cuerpo = await respuesta.json();
            const lista = document.getElementById('lista-alimentos');
            lista.innerHTML = '';

            cuerpo.alimentos.data.forEach((alimento) => {
                const item = document.createElement('div');
                item.className = 'item-alimento';

                const nombre = document.createElement('strong');
                nombre.textContent = alimento.nombre;

                const categoria = document.createElement('span');
                categoria.textContent = alimento.categoria ? alimento.categoria.nombre : 'Sin categoría';

                const descripcion = document.createElement('small');
                descripcion.textContent = alimento.descripcion || '';

                const acciones = document.createElement('div');
                acciones.className = 'acciones-item';

                const btnPedir = document.createElement('button');
                btnPedir.type = 'button';
                btnPedir.className = 'boton-chico';
                btnPedir.textContent = 'Pedir';
                btnPedir.addEventListener('click', () => abrirFormulario(alimento, 'pedir'));

                const btnDonar = document.createElement('button');
                btnDonar.type = 'button';
                btnDonar.className = 'boton-chico donar';
                btnDonar.textContent = 'Donar';
                btnDonar.addEventListener('click', () => abrirFormulario(alimento, 'donar'));

                acciones.append(btnPedir, btnDonar);
                item.append(nombre, categoria, descripcion, acciones);
                lista.appendChild(item);
            });

            if (!cuerpo.alimentos.data.length) {
                lista.innerHTML = '<p>No hay alimentos disponibles por el momento.</p>';
            }
        }

        function abrirFormulario(alimento, accion) {
            alimentoActual = alimento;

            document.getElementById('titulo-formulario').textContent =
                accion === 'pedir' ? 'Pedir alimento' : 'Donar alimento';
            document.getElementById('alimento-formulario').textContent =
                alimento.nombre + (alimento.categoria ? ' — ' + alimento.categoria.nombre : '');

            document.getElementById('form-pedir').hidden = accion !== 'pedir';
            document.getElementById('form-donar').hidden = accion !== 'donar';
            document.getElementById('mensaje-formulario').hidden = true;
            document.getElementById('mensaje-formulario-donacion').hidden = true;

            mostrarVista('formulario');
        }

        async function enviarRegistro(url, datos, idMensaje) {
            const mensaje = document.getElementById(idMensaje);
            mensaje.hidden = false;
            mensaje.classList.remove('exito');

            const respuesta = await pedir(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datos),
            });
            if (!respuesta) return;

            const cuerpo = await respuesta.json().catch(() => ({}));

            if (respuesta.ok) {
                mensaje.textContent = cuerpo.message || 'Registrado correctamente';
                mensaje.classList.add('exito');
            } else {
                mensaje.textContent = mensajeError(cuerpo);
            }
        }

        async function cargarPedidos() {
            const respuesta = await pedir('/api/v1/cliente/solicitudes');
            if (!respuesta) return;

            const cuerpo = await respuesta.json();
            const lista = document.getElementById('lista-pedidos');
            lista.innerHTML = '';

            cuerpo.solicitudes.data.forEach((solicitud) => {
                const item = document.createElement('div');
                item.className = 'item-alimento';

                const encabezado = document.createElement('strong');
                encabezado.textContent = solicitud.fecha_solicitud + ' — ' + solicitud.estado;

                const direccion = document.createElement('span');
                direccion.textContent = solicitud.direccion_entrega;

                item.append(encabezado, direccion);

                solicitud.detalles.forEach((detalle) => {
                    const linea = document.createElement('small');
                    linea.textContent = (detalle.alimento ? detalle.alimento.nombre : 'Alimento')
                        + ' × ' + detalle.cantidad;
                    item.appendChild(linea);
                });

                lista.appendChild(item);
            });

            if (!cuerpo.solicitudes.data.length) {
                lista.innerHTML = '<p>Aún no has realizado pedidos. Elige un alimento del catálogo.</p>';
            }
        }

        async function cargarDonaciones() {
            const respuesta = await pedir('/api/v1/cliente/donaciones');
            if (!respuesta) return;

            const cuerpo = await respuesta.json();
            const lista = document.getElementById('lista-donaciones');
            lista.innerHTML = '';

            cuerpo.donaciones.data.forEach((donacion) => {
                const item = document.createElement('div');
                item.className = 'item-alimento';

                const encabezado = document.createElement('strong');
                encabezado.textContent = donacion.fecha_donacion + ' — ' + donacion.estado;

                const ubicacion = document.createElement('span');
                ubicacion.textContent = donacion.ubicacion + ' (hasta ' + donacion.fecha_limite + ')';

                item.append(encabezado, ubicacion);

                donacion.detalles.forEach((detalle) => {
                    const linea = document.createElement('small');
                    linea.textContent = (detalle.alimento ? detalle.alimento.nombre : 'Alimento')
                        + ' × ' + detalle.cantidad;
                    item.appendChild(linea);
                });

                lista.appendChild(item);
            });

            if (!cuerpo.donaciones.data.length) {
                lista.innerHTML = '<p>Aún no has registrado donaciones. Elige un alimento del catálogo.</p>';
            }
        }

        document.getElementById('form-pedir').addEventListener('submit', async (evento) => {
            evento.preventDefault();

            const datos = Object.fromEntries(new FormData(evento.target));
            datos.id_alimento = alimentoActual.id_alimento;

            await enviarRegistro('/api/v1/cliente/solicitudes', datos, 'mensaje-formulario');
        });

        document.getElementById('form-donar').addEventListener('submit', async (evento) => {
            evento.preventDefault();

            const datos = Object.fromEntries(new FormData(evento.target));
            datos.id_alimento = alimentoActual.id_alimento;

            await enviarRegistro('/api/v1/cliente/donaciones', datos, 'mensaje-formulario-donacion');
        });

        document.getElementById('btn-volver').addEventListener('click', () => mostrarVista('alimentos'));
        document.getElementById('btn-volver-donacion').addEventListener('click', () => mostrarVista('alimentos'));

        document.getElementById('form-perfil').addEventListener('submit', async (evento) => {
            evento.preventDefault();

            const mensaje = document.getElementById('mensaje-perfil');
            mensaje.hidden = false;
            mensaje.classList.remove('exito');

            const datos = Object.fromEntries(new FormData(evento.target));

            const respuesta = await pedir('/api/v1/cliente/perfil', {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datos),
            });
            if (!respuesta) return;

            const cuerpo = await respuesta.json().catch(() => ({}));

            if (respuesta.ok) {
                mensaje.textContent = 'Perfil actualizado correctamente';
                mensaje.classList.add('exito');
                cargarPerfil();
            } else {
                mensaje.textContent = mensajeError(cuerpo);
            }
        });

        document.getElementById('btn-alimentos').addEventListener('click', () => mostrarVista('alimentos'));
        document.getElementById('btn-pedidos').addEventListener('click', () => mostrarVista('pedidos'));
        document.getElementById('btn-donaciones').addEventListener('click', () => mostrarVista('donaciones'));
        document.getElementById('btn-perfil').addEventListener('click', () => mostrarVista('perfil'));

        document.getElementById('btn-salir').addEventListener('click', async () => {
            await pedir('/api/v1/cliente/logout', { method: 'POST' });
            localStorage.clear();
            window.location.href = '/cliente';
        });

        cargarPerfil();
        mostrarVista('alimentos');
    </script>
</body>
</html>
