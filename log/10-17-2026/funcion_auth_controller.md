# Auth Controller

## este archivo es el controlador del login del area administrativa (app/Http/Controllers/AuthAdminController.php)

### - login(): muestra el formulario si no hay sesion

### - in(): valida username y password, checa que la cuenta este activa y crea la sesion

### - out(): cierra la sesion con el guard admin

## mensajes que usa (van con ->with() en la sesion)

### - inicio de sesion exitoso (exito)

### - usuario o contrasena incorrectos (aviso)

### - la cuenta esta deshabilitada (aviso)

### - cierre de sesion exitoso (exito)

## favor de leer lo siguiente

### - los mensajes van con ->with() y se muestran con @session en el layout, nunca con texto plano en la vista

### - el password minimo es8 caracteres y el username maximo30 (igual que la columna de la tabla)

### - al entrar se valida session()->regenerate() y en el logout invalidate() + regenerateToken()

### - si cambias un mensaje acuerdate de actualizar el test, ahi tambien se verifica

---

### cualquier duda preguntar a mi --Adrian--

#### esta funcion fue hecha el 10/17/2026
