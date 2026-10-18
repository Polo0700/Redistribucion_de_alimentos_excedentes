# Modificacion de blade

## este cambio fue en el archivo resources/views/layouts/app.blade.php que es el menu principal del proyecto

### - ahora el menu cambia segun si hay sesion de administrador o no

### - @guest('admin') si no hay sesion solo muestra el link de iniciar sesion

### - @auth('admin') si hay sesion muestra el dropdown con nombre, username y cerrar sesion

### - @session('exito') y @session('aviso') muestran los mensajes temporales del controller (verde y rojo)

## cambios breves

### - el dropdown ahora usa nombre_completo y username del modelo Administrador (antes traian campos viejos)

### - los mensajes de sesion salen arriba del contenido y se ven en todas las paginas

## favor de leer lo siguiente

### - estas directivas solo cambian lo que se ve en pantalla, las rutas siguen protegidas con el middleware auth:admin

---

### cualquier duda preguntar a mi --Adrian--

#### este cambio fue hecho el 10/17/2026
