# Evidencias - capturas y video

## fecha: 10/17/2026

## cuentas de prueba

- admin / admin123 (activa, puede entrar)
- inactivo / admin123 (deshabilitada, no puede entrar)

---

## archivos que se tocaron en la actividad

### base de datos
- database/migrations/2026_10_17_053655_create_administradores_table.php (tabla administradores)
- database/seeders/AdministradorSeeder.php (2 cuentas de prueba con Hash::make)

### backend
- app/Models/Administrador.php (modelo de la tabla)
- app/Http/Controllers/AuthAdminController.php (login, logout y mensajes)
- config/auth.php (guard admin + provider al modelo Administrador)
- routes/web.php (rutas protegidas con middleware auth:admin)
- bootstrap/app.php (redireccion al login para invitados)

### vistas
- resources/views/admin/login.blade.php (formulario con @csrf)
- resources/views/layouts/app.blade.php (menu con @auth/@guest y mensajes @session)

### pruebas y documentacion
- tests/Feature/AdminAuthTest.php (8 pruebas del login)
- README.md (instrucciones de instalacion)
- log/10-17-2026/ (7 archivos de logs explicando cada pieza)

---

## como correr el proyecto

```
git clone https://github.com/Polo0700/Redistribucion_de_alimentos_excedentes.git
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed --class=AdministradorSeeder
php artisan serve
```

entrar a: http://127.0.0.1:8000/admin/login

---

## lista de capturas (todas con fecha y hora visibles en pantalla)

- [ ] 1. tabla administradores en phpmyadmin (con las2 filas)
- [ ] 2. el archivo del seeder mostrando el Hash::make
- [ ] 3. config/auth.php con el guard admin y el provider Administrador
- [ ] 4. formulario de login
- [ ] 5. login exitoso (mensaje verde "Inicio de sesion exitoso" + menu completo)
- [ ] 6. credenciales incorrectas (mensaje rojo)
- [ ] 7. cuenta deshabilitada (mensaje rojo "La cuenta esta deshabilitada")
- [ ] 8. ruta protegida sin sesion (escribir URL directo y que regrese al login)
- [ ] 9. cierre de sesion (mensaje verde "Cierre de sesion exitoso")
- [ ] 10. php artisan test con las33 pruebas verdes (opcional pero bonito)

---

## lista del video (maximo5 min, subirlo a drive o youtube)

- [ ] formulario de login (~30 seg)
- [ ] autenticacion correcta con admin/admin123 (~45 seg)
- [ ] rechazo de password incorrecta (~30 seg)
- [ ] bloqueo de cuenta deshabilitada (~30 seg)
- [ ] proteccion de rutas: URL directo sin sesion (~30 seg)
- [ ] menu adaptado: invitado vs con sesion (~45 seg)
- [ ] mantenimiento de sesion: navegar entre paginas (~30 seg)
- [ ] cierre de sesion (~30 seg)

---

## para el PDF

- portada: asignatura, actividad, nombres ordenados alfabeticamente
- pagina2: enlace al repositorio + enlace al video
- anexos: las capturas con su tabla de descripcion

## no olvidar

- el video NO se sube al repo, solo el enlace
- solo un integrante entrega
