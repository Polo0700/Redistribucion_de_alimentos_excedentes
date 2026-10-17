# A3_2 — Mostrar, Borrado Lógico y Borrado Físico

Tickets de implementación en orden. Marca con `[x]` lo que completes.
Cuando te atores, lee la "Ayuda técnica" al final.

---

## FASE 0 — Base (haz esto primero, una sola vez)

- [ ] **T0.1 Migración `deleted_at`** para las 12 tablas.
  - Crear una migración por tabla o una sola con `Schema::table` por cada tabla:

```bash
php artisan make:migration add_deleted_at_a_tablas_actividad
```

```php
// database/migrations/xxxx_add_deleted_at_a_tablas_actividad.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tablas = [
            'roles', 'categorias_alimento', 'usuarios', 'alimentos',
            'cuentas_acceso', 'acciones_importantes', 'carritos',
            'listas_deseos', 'solicitudes', 'detalle_solicitud',
            'donaciones', 'detalle_donacion', 'carrito_detalle',
            'deseos_detalle', 'entregas',
        ];
        foreach ($tablas as $tabla) {
            Schema::table($tabla, fn (Blueprint $t) => $t->softDeletes());
        }
    }

    public function down(): void
    {
        $tablas = [ /* mismas */ ];
        foreach ($tablas as $tabla) {
            Schema::table($tabla, fn (Blueprint $t) => $t->dropSoftDeletes());
        }
    }
};
```

  > Importante: `detalle_solicitud`, `detalle_donacion`, `carrito_detalle`, `deseos_detalle` no son "entidades del listado", pero agregar `deleted_at` no estorba. Lo realmente necesario son las entidades con listado: **roles, categorias_alimento, usuarios, alimentos, cuentas_acceso, acciones_importantes, carritos, listas_deseos, solicitudes, donaciones, entregas**.

- [ ] **T0.2 Agregar `SoftDeletes` a los modelos.**
  - En cada modelo (ej. `app/Models/Alimento.php`):

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class Alimento extends Model
{
    use SoftDeletes;
    // usa lo demás igual
}
```

  - Modelos: Rol, CategoriaAlimento, Usuario, Alimento, CuentaAcceso, AccionImportante, Carrito, ListaDeseo, Solicitud, Donacion, Entrega.

- [ ] **T0.3 Correr la migración**

```bash
php artisan migrate
```

  > Con `SoftDeletes`, los `index()` actuales ya solo muestran registros activos (los que no tienen `deleted_at`). Eso cumple solo el requisito de "listado de activos". No hay que tocar los index.

---

## FASE 1 — Implementar TODO en Alimentos (tu modelo de referencia)

Usa Alimento como plantilla: tiene imagen y relaciones. Si queda bien aquí, copias el patrón a las demás.

### Mostrar

- [ ] **T1.1 Método `show`** en `app/Http/Controllers/AlimentoController.php`

```php
use App\Models\Alimento;
use App\Models\CategoriaAlimento;
use Illuminate\Support\Facades\Storage;

public function show($id)
{
    $alimento = Alimento::with('categoria')->findOrFail($id);
    return view('alimentos.show', compact('alimento'));
}
```

- [ ] **T1.2 Vista `resources/views/alimentos/show.blade.php`**
  - Muestra todos los atributos, la categoría (relación) y la imagen.
  - Sin inputs editables (solo lectura) + botón "Volver".
  - Copia el diseño del `index` (Tailwind).

```blade
@extends('layouts.app')

@section('title', 'Detalle de ' . $alimento->nombre)

@section('content')
    <h1 class="text-3xl font-bold text-gray-900">Detalle del alimento</h1>

    <div class="mt-6 bg-white border border-gray-200 rounded-lg shadow-sm p-6">
        @if ($alimento->imagen)
            <img src="{{ asset('storage/' . $alimento->imagen) }}"
                 alt="{{ $alimento->nombre }}" class="w-40 h-40 object-cover rounded-lg">
        @else
            <span class="text-xs text-gray-400">Sin imagen</span>
        @endif

        <dl class="mt-6 space-y-4">
            <div><dt class="text-xs uppercase text-gray-400">ID</dt>
            <dd class="text-gray-900">{{ $alimento->id_alimento }}</dd></div>

            <div><dt class="text-xs uppercase text-gray-400">Nombre</dt>
            <dd class="text-gray-900">{{ $alimento->nombre }}</dd></div>

            <div><dt class="text-xs uppercase text-gray-400">Descripción</dt>
            <dd class="text-gray-900">{{ $alimento->descripcion }}</dd></div>

            {{-- Relación con categoría --}}
            <div><dt class="text-xs uppercase text-gray-400">Categoría</dt>
            <dd class="text-gray-900">{{ $alimento->categoria->nombre ?? 'Sin categoría' }}</dd></div>

            <div><dt class="text-xs uppercase text-gray-400">Estado</dt>
            <dd class="text-gray-900">{{ $alimento->estado ? 'Activo' : 'Inactivo' }}</dd></div>
        </dl>

        <a href="{{ route('alimentos.index') }}" class="mt-6 inline-block px-5 py-2.5 text-sm font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-900">Volver</a>
    </div>
@endsection
```

- [ ] **T1.3 Enlace del botón "Ver"** en `alimentos/index.blade.php` → convertir el botón muerto en link a `route('alimentos.show', $alimento->id_alimento)`.

### Borrado lógico + papelera + restaurar + físico

- [ ] **T1.4 Métodos de borrado** en el controller:

```php
// Borrado lógico: solo pone deleted_at
public function destroy($id)
{
    $alimento = Alimento::findOrFail($id);
    $alimento->delete();
    return redirect()->route('alimentos.index')
        ->with('success', 'Alimento eliminado lógicamente.');
}

// Lista de eliminados (papelera)
public function trashed()
{
    $alimentos = Alimento::onlyTrashed()->paginate(5);
    return view('alimentos.trashed', compact('alimentos'));
}

// Restauración
public function restore($id)
{
    $alimento = Alimento::onlyTrashed()->findOrFail($id);
    $alimento->restore();
    return redirect()->route('alimentos.trashed')
        ->with('success', 'Alimento restaurado correctamente.');
}

// Borrado físico: SOLO si está eliminado lógicamente + validar relaciones + borrar imagen
public function forceDestroy($id)
{
    $alimento = Alimento::withTrashed()->findOrFail($id);

    if (!$alimento->trashed()) {
        return redirect()->back()
            ->with('error', 'Primero se debe eliminar lógicamente el registro.');
    }

    // VALIDACIÓN DE RELACIONES
    if ($alimento->detallesDonacion()->count() > 0) {
        return redirect()->back()
            ->with('error', 'No se puede eliminar: el alimento está registrado en Detalles de Donación.');
    }
    if ($alimento->detallesCarrito()->count() > 0) {
        return redirect()->back()
            ->with('error', 'No se puede eliminar: el alimento está en Carritos.');
    }
    if ($alimento->deseosDetalle()->count() > 0) {
        return redirect()->back()
            ->with('error', 'No se puede eliminar: el alimento está en Listas de Deseos.');
    }

    // ELIMINAR IMAGEN del storage antes de borrar el registro
    if ($alimento->imagen && Storage::disk('public')->exists($alimento->imagen)) {
        Storage::disk('public')->delete($alimento->imagen);
    }

    $alimento->forceDelete();
    return redirect()->route('alimentos.trashed')
        ->with('success', 'Alimento eliminado físicamente (junto con su imagen).');
}
```

- [ ] **T1.5 Vista `alimentos/trashed.blade.php`** (papelera)
  - Igual que el index pero con `Alimento: {{ ... }}` y **solo** los eliminados.
  - Botones por fila: **Restaurar** (POST a `alimentos.restore`) y **Eliminar definitivamente** (DELETE a `alimentos.forceDestroy`), ambos con confirmación.

```blade
@extends('layouts.app')

@section('title', 'Alimentos eliminados')

@section('content')
    @if (session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 bg-green-100 border border-green-200 rounded-lg">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 bg-red-100 border border-red-200 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Alimentos eliminados</h1>
            <p class="mt-1 text-gray-500">Registros con borrado lógico</p>
        </div>
        <a href="{{ route('alimentos.index') }}" class="px-5 py-2.5 text-sm font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-900">Volver al listado</a>
    </div>

    <div class="relative overflow-x-auto bg-white border border-gray-200 rounded-lg shadow-sm">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Categoría</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($alimentos as $alimento)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $alimento->id_alimento }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $alimento->nombre }}</td>
                        <td class="px-6 py-4">{{ $alimento->categoria->nombre ?? 'Sin categoría' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                {{-- Restaurar --}}
                                <form action="{{ route('alimentos.restore', $alimento->id_alimento) }}" method="POST"
                                      onsubmit="return confirm('¿Restaurar este alimento?')">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium text-green-700 bg-green-100 rounded-lg">Restaurar</button>
                                </form>

                                {{-- Borrado físico --}}
                                <form action="{{ route('alimentos.forceDestroy', $alimento->id_alimento) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar DEFINITIVAMENTE? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-lg">Eliminar definitivamente</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $alimentos->links() }}</div>
@endsection
```

- [ ] **T1.6 Confirmación en el listado** → convertir el botón "Eliminar" de `index.blade.php` en un formulario POST con confirmación:

```blade
<form action="{{ route('alimentos.destroy', $alimento->id_alimento) }}" method="POST"
      onsubmit="return confirm('¿Eliminar este alimento? Se puede restaurar después.')">
    @csrf
    @method('DELETE')
    <button type="submit" class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-lg">Eliminar</button>
</form>
```

- [ ] **T1.7 Nuevas rutas** en `routes/web.php` (bloque de Alimentos):

```php
Route::get('/alimentos', [AlimentoController::class, 'index'])->name('alimentos.index');
Route::get('/alimentos/eliminados', [AlimentoController::class, 'trashed'])->name('alimentos.trashed');
Route::get('/alimentos/create', [AlimentoController::class, 'create'])->name('alimentos.create');
Route::post('/alimentos', [AlimentoController::class, 'store'])->name('alimentos.store');
Route::get('/alimentos/{id}', [AlimentoController::class, 'show'])->name('alimentos.show');
Route::get('/alimentos/{id}/edit', [AlimentoController::class, 'edit'])->name('alimentos.edit');
Route::put('/alimentos/{id}', [AlimentoController::class, 'update'])->name('alimentos.update');
Route::delete('/alimentos/{id}', [AlimentoController::class, 'destroy'])->name('alimentos.destroy');
Route::patch('/alimentos/{id}/restore', [AlimentoController::class, 'restore'])->name('alimentos.restore');
Route::delete('/alimentos/{id}/force', [AlimentoController::class, 'forceDestroy'])->name('alimentos.forceDestroy');
```

> Importante: la ruta `{id}` (show) va DESPUÉS de `/alimentos/eliminados`, si no Laravel interpreta "eliminados" como un id.

- [ ] **T1.8 Enlace a la papelera** desde el index (botón "Ver eliminados") y desde el sidebar si quieres.
- [ ] **T1.9 Probar todo en navegador** (checklist de pruebas abajo).

---

## FASE 2 — Replicar a las 11 entidades restantes

Para cada una repite: `show`, `destroy`, `trashed`, `restore`, `forceDestroy` + vistas `show` y `trashed` + rutas + enlaces.

Relaciones a validar en el borrado físico (según tus modelos):

| Entidad | Relaciones dependientes a revisar |
|---|---|
| Alimento | detallesDonacion, detallesCarrito, deseosDetalle |
| CategoriaAlimento | alimentos |
| Usuario | cuentasAcceso, donaciones, carritos, listasDeseos, accionesImportantes |
| Rol | usuarios |
| Carrito | detalles |
| Donacion | detalles |
| ListaDeseo | deseos |
| CuentaAcceso | (ninguna hija) |
| AccionImportante | (ninguna) |
| Solicitud | (ninguna definida) |
| DetalleSolicitud | (ninguna) |
| Entrega | (ninguna) |

Las que no tienen dependencias, el borrado físico corre directo. Las que sí, bloquear con mensaje claro (ver T1.4).

> Usuario y CategoriaAlimento son los otros dos casos con imagen/dependencias fuertes. Recomendado hacer Alimento → CategoriaAlimento → Usuario → resto.

---

## FASE 3 — Pruebas de funcionamiento

- [ ] La vista Mostrar presenta toda la información (relaciones + imagen) y no deja editar.
- [ ] El borrado lógico solo pone `deleted_at` (verifícalo en la BD o con tinker: `Alimento::find($id)->trashed()` devuelve `true`).
- [ ] El registro eliminado desaparece del listado principal.
- [ ] Aparece en la vista de eliminados y se puede restaurar (vuelve al listado).
- [ ] El borrado físico lo quita de la BD **y borra el archivo de imagen** de `storage/app/public/alimentos`.
- [ ] Si el alimento está en donaciones/carritos/deseos, el borrado físico se cancela con mensaje de error.

---

## FASE 4 — Publicación y entrega

- [ ] `git add . && git commit` y `git push` a GitHub.
- [ ] Video (máx. 5 min, público) mostrando: Mostrar, borrado lógico, papelera, restauración, borrado físico, validación de relaciones, eliminación de imágenes, mensajes y confirmaciones.
- [ ] PDF `EquipoN_A3_2_Mostrar_Borrado.pdf`: portada (integrantes en orden A-Z por primer apellido), página de enlaces (GitHub + video), anexo con capturas CON fecha y hora visibles.

---

# AYUDA TÉCNICA

## Fórmula de consultas (SoftDeletes)

```php
MiModelo::paginate(5);           // solo activos (por defecto)
MiModelo::onlyTrashed()->paginate(5); // solo eliminados
MiModelo::withTrashed()->findOrFail($id); // cualquiera (restaurar/borrar físico)
$reg->trashed();                 // true si está lógicamente eliminado
$reg->delete();                  // borrado lógico
$reg->restore();                 // restaurar
$reg->forceDelete();             // borrado físico
```

## Borrado físico con validación (patrón genérico)

```php
public function forceDestroy($id)
{
    $reg = MiModelo::withTrashed()->findOrFail($id);

    if (!$reg->trashed()) {
        return back()->with('error', 'Primero aplica el borrado lógico.');
    }

    // Valida cada relación: "si tiene hijos, cancelo"
    if ($reg->relacionHija()->count() > 0) {
        return back()->with('error', 'No se puede eliminar: hay registros dependientes en ....');
    }

    // Imagen (solo si el modelo tiene campo imagen)
    if ($reg->imagen && Storage::disk('public')->exists($reg->imagen)) {
        Storage::disk('public')->delete($reg->imagen);
    }

    $reg->forceDelete();
    return back()->with('success', 'Registro eliminado definitivamente.');
}
```

## Mensajes flash

```php
->with('success', 'Operación correcta');   // verde en la vista
->with('error',   'No se puede realizar'); // rojo en la vista
```

En la vista, agrega ambos bloques (ver T1.5). El `error` es el que hace falta en tus vista actuales (solo tienes `success`).

## `back()` vs `redirect()`
- `back()` → vuelve a la pantalla anterior (útil en la papelera).
- `redirect()->route('x.index')` → va a una ruta fija.

## Trampas comunes
1. **Orden de rutas:** la de `{id}` (show) siempre DESPUÉS de `/alimentos/eliminados`.
2. **Métodos HTTP:** destroy/force = `DELETE`, restore = `PATCH`. En los formularios usa `@method('DELETE'/'PATCH')` siempre con `@csrf`.
3. **`withTrashed()` es obligatorio** para restaurar y borrar en físico: un registro eliminado no lo encuentra `findOrFail()` normal.
4. **Importar clases:** `Illuminate\Support\Facades\Storage` en controllers que borren imágenes.
5. **No borrar imagen hasta fallar la validación:** primero valida relaciones, luego borra imagen, luego `forceDelete()`.