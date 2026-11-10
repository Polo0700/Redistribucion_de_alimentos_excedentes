# Catálogo de endpoints — API REST

Plantilla de documentación por endpoint. Copiar el bloque en blanco y rellenar.

## Estructura en blanco (copiar por cada endpoint)

```markdown
### [IDENTIFICADOR]
- **Área:**
- **Método + URL:**
- **Descripción:**
- **Autenticación:**
- **Autorización:**
- **Parámetros de ruta:**
- **Parámetros de consulta:**
- **Encabezados:**
- **Cuerpo (body):**
- **Respuesta exitosa:**
- **Errores posibles:**
```

---

## Ejemplo rellenado (seguir este patrón)

### admin.auth.login
- **Área:** administrativa
- **Método + URL:** POST /api/v1/admin/login
- **Descripción:** Autentica a un administrador y emite un token Bearer (Sanctum) para consumir los endpoints protegidos del área administrativa.
- **Autenticación:** pública (es el endpoint que otorga el token)
- **Autorización:** ninguna; cualquier cuenta admin activa puede iniciar sesión
- **Parámetros de ruta:** ninguno
- **Parámetros de consulta:** ninguno
- **Encabezados:** Content-Type: application/json
- **Cuerpo (body):**
  ```json
  {
    "username": "string, obligatorio, máx. 30 caracteres",
    "password": "string, obligatorio, mín. 8 caracteres"
  }
  ```
- **Respuesta exitosa:** 200 OK
  ```json
  {
    "token": "1|eyJpYXQiOjE3NDk5...",
    "admin": { "id": 1, "username": "admin" }
  }
  ```
- **Errores posibles:**
  - 401 → `{"message": "Credenciales incorrectas"}` — username o password no coinciden
  - 403 → `{"message": "Cuenta deshabilitada"}` — admin existe pero `activo = false`
  - 422 → `{"errors": {"username": ["..."]}}` — falta un campo o no cumple validación
