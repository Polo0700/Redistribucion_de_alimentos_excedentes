{{-- ============================================================ --}}
{{-- VISTA: TRASHED (Registros eliminados / papelera)          --}}
{{-- ============================================================ --}}
{{-- QUÉ DEBE CONTENER:                                          --}}
{{-- - @extends('layouts.app') y @section('content').            --}}
{{-- - Mensajes flash de success Y error.                        --}}
{{-- - Tabla con SOLO registros eliminados (llegan de            --}}
{{--   Modelo::onlyTrashed()).                                    --}}
{{-- - Por cada fila, 2 formularios con confirmación:            --}}
{{--   1) RESTAURAR: POST a ruta '...restore'                    --}}
{{--      onsubmit="return confirm('¿Restaurar?')"               --}}
{{--   2) BORRADO FÍSICO: DELETE a ruta '...forceDestroy'        --}}
{{--      onsubmit="return confirm('¿Eliminar DEFINITIVAMENTE?')" --}}
{{-- - @csrf en ambos y @method('DELETE') en el físico.          --}}
{{-- - Botón "Volver al listado".                                --}}
{{-- ============================================================ --}}

@extends('layouts.app')

@section('title', 'Registros eliminados')

@section('content')
    {{-- LLENAR AQUÍ --}}
@endsection