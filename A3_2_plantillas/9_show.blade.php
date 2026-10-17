{{-- ============================================================ --}}
{{-- VISTA: SHOW (Mostrar) — SOLO LECTURA —                     --}}
{{-- ============================================================ --}}
{{-- QUÉ DEBE CONTENER:                                          --}}
{{-- - @extends('layouts.app') y @section('content').            --}}
{{-- - Mostrar TODOS los atributos del registro.                 --}}
{{-- - Mostrar la información de las RELACIONES                  --}}
{{--   (ej: {{ $alimento->categoria->nombre }}).                 --}}
{{-- - Mostrar la IMAGEN si existe:                              --}}
{{--   @if ($alimento->imagen) <img src="{{ asset('storage/' .   --}}
{{--   $alimento->imagen ) }}"> @endif                            --}}
{{-- - NO permitir modificaciones (sin inputs).                  --}}
{{-- - Botón "Volver" al index.                                  --}}
{{-- ============================================================ --}}

@extends('layouts.app')

@section('title', 'Detalle')

@section('content')
    {{-- LLENAR AQUÍ --}}
@endsection