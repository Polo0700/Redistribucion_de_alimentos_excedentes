# Test Admin Auth

## este archivo son las pruebas automaticas del login (tests/Feature/AdminAuthTest.php)

### - se corren con php artisan test

### - usa una base en memoria (sqlite) asi que no necesita XAMPP abierto

## pruebas que incluye (8)

### - el formulario carga

### - login valido entra y manda al dashboard

### - password incorrecta no entra

### - cuenta deshabilitada no entra

### - campos requeridos validan

### - logout cierra sesion y manda al login

### - rutas sin sesion mandan al login (proteccion)

### - rutas con sesion dejan pasar

## favor de leer lo siguiente

### - si cambias campos o mensajes del login acuerdate de actualizar este test o va a tronar

### - el dia de hoy salio todo verde con las33 pruebas del proyecto

---

### cualquier duda preguntar a mi --Adrian--

#### estas pruebas fueron hechas el 10/17/2026
