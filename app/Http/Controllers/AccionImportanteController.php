<?php

namespace App\Http\Controllers;

use App\Models\AccionImportante;
use App\Models\Usuario;
use Illuminate\Http\Request;

class AccionImportanteController extends Controller
{
    // trae las acciones importantes de la base de datos con su usuario de 5 en 5
    public function index(Request $request)
    {
        $acciones = AccionImportante::with('usuario')->paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['acciones' => $acciones], 200);
        }

        // retorna la pagina del index de acciones
        return view('acciones-importantes.index', compact('acciones'));
    }

    // obtiene los usuarios y los ordena por nombre y los mete a usuarios
    public function create(Request $request)
    {
        $usuarios = Usuario::orderBy('nombre')->get();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['usuarios' => $usuarios], 200);
        }

        // retorna la vista de creacion de acciones
        return view('acciones-importantes.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros
        $validado = $request->validate([
            'id_usuario' => 'nullable|exists:usuarios,id',
            'accion' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:250',
            'tabla_afectada' => 'required|string|max:80',
            'fecha_hora' => 'required|date',
            'ip_origen' => 'required|ip|max:45',
        ]);

        // crea una accion importante
        AccionImportante::create($validado);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Accion importante creada correctamente'], 201);
        }

        // redirige al index y un mensaje de correcto
        return redirect()->route('acciones.index')->with('success', 'Accion importante creada correctamente');
    }

    public function edit(Request $request, $id)
    {
        $accion = AccionImportante::findOrFail($id);

        $usuarios = Usuario::where('estado', true)->get();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['accion' => $accion, 'usuarios' => $usuarios], 200);
        }

        return view(
            'acciones-importantes.edit',
            compact('accion', 'usuarios')
        );
    }

    public function update(Request $request, $id)
    {
        $accion = AccionImportante::findOrFail($id);

        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id',
            'accion' => 'required|max:100',
            'tabla_afectada' => 'required|max:80',
            'descripcion' => 'nullable|max:250',
            'fecha_hora' => 'required|date',
            'ip_origen' => 'nullable|ip',
        ]);

        $accion->update([
            'id_usuario' => $request->id_usuario,
            'accion' => $request->accion,
            'tabla_afectada' => $request->tabla_afectada,
            'descripcion' => $request->descripcion,
            'fecha_hora' => $request->fecha_hora,
            'ip_origen' => $request->ip_origen,
        ]);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Acción actualizada correctamente.'], 200);
        }

        return redirect()
            ->route('acciones.index')
            ->with('success', 'Acción actualizada correctamente.');
    }

    // retorna la vista show de la accion
    public function show(Request $request, $id)
    {
        $accion = AccionImportante::with('usuario')->findOrFail($id);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['accion' => $accion], 200);
        }

        return view('acciones-importantes.show', compact('accion'));
    }

    // borra la accion importante logicamente
    public function destroy(Request $request, $id)
    {
        $accion = AccionImportante::findOrFail($id);
        $accion->delete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Acción importante eliminada correctamente.'], 200);
        }

        return redirect()->route('acciones.index')->with('success', 'Acción importante eliminada correctamente.');
    }

    // trae los eliminados logicamente de 5 en 5
    public function trashed(Request $request)
    {
        $acciones = AccionImportante::onlyTrashed()->paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['acciones' => $acciones], 200);
        }

        return view('acciones-importantes.trashed', compact('acciones'));
    }

    // regresa la accion a la tabla
    public function restore(Request $request, $id)
    {
        $accion = AccionImportante::onlyTrashed()->findOrFail($id);
        $accion->restore();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Acción importante restaurada correctamente.'], 200);
        }

        return redirect()->route('acciones.trashed')->with('success', 'Acción importante restaurada correctamente.');
    }

    // borra el registro de la tabla por completo
    public function forceDestroy(Request $request, $id)
    {
        $accion = AccionImportante::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminada lógicamente
        if (! $accion->trashed()) {
            // en api devuelve json
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
            }

            return redirect()->route('acciones.trashed')->with('error', 'Primero debe aplicar el borrado lógico al registro.');
        }

        // sin tablas dependientes: se elimina directamente
        $accion->forceDelete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Acción importante eliminada definitivamente.'], 200);
        }

        return redirect()->route('acciones.trashed')->with('success', 'Acción importante eliminada definitivamente.');
    }
}
