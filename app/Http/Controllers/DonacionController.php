<?php

namespace App\Http\Controllers;

use App\Models\Donacion;
use App\Models\Usuario;
use Illuminate\Http\Request;

class DonacionController extends Controller
{
    // trae las donaciones de la base de datos con su usuario de 5 en 5
    public function index(Request $request)
    {
        $donaciones = Donacion::with('usuario')->paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['donaciones' => $donaciones], 200);
        }

        // retorna la pagina del index de donaciones
        return view('donaciones.index', compact('donaciones'));
    }

    // obtiene los usuarios y los ordena por nombre y los mete a usuarios
    public function create(Request $request)
    {
        $usuarios = Usuario::orderBy('nombre')->get();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['usuarios' => $usuarios], 200);
        }

        // retorna la vista de creacion de donaciones
        return view('donaciones.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros y que las fechas esten en orden
        $validado = $request->validate([
            'id_usuario' => 'required|exists:usuarios,id',
            'fecha_donacion' => 'required|date',
            'fecha_limite' => 'required|date|after:fecha_donacion',
            'ubicacion' => 'required|string|max:200',
            'estado' => 'required|string|max:30',
            'observaciones' => 'required|string|max:250',
        ]);

        // crea una donacion
        Donacion::create($validado);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Donacion creada correctamente'], 201);
        }

        // redirige al index y un mensaje de correcto
        return redirect()->route('donaciones.index')->with('success', 'Donacion creada correctamente');
    }

    public function edit(Request $request, $id)
    {
        $donacion = Donacion::findOrFail($id);

        $usuarios = Usuario::where('estado', true)->get();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['donacion' => $donacion, 'usuarios' => $usuarios], 200);
        }

        return view(
            'donaciones.edit',
            compact('donacion', 'usuarios')
        );
    }

    public function update(Request $request, $id)
    {
        $donacion = Donacion::findOrFail($id);

        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id',
            'fecha_donacion' => 'required|date',
            'fecha_limite' => 'required|date',
            'ubicacion' => 'required|max:200',
            'estado' => 'required|max:30',
            'observaciones' => 'nullable|max:250',
        ]);

        $donacion->update([
            'id_usuario' => $request->id_usuario,
            'fecha_donacion' => $request->fecha_donacion,
            'fecha_limite' => $request->fecha_limite,
            'ubicacion' => $request->ubicacion,
            'estado' => $request->estado,
            'observaciones' => $request->observaciones,
        ]);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Donación actualizada correctamente.'], 200);
        }

        return redirect()
            ->route('donaciones.index')
            ->with('success', 'Donación actualizada correctamente.');
    }

    // retorna la vista show de la donacion
    public function show(Request $request, $id)
    {
        $donacion = Donacion::with('usuario')->findOrFail($id);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['donacion' => $donacion], 200);
        }

        return view('donaciones.show', compact('donacion'));
    }

    // borra la donacion logicamente
    public function destroy(Request $request, $id)
    {
        $donacion = Donacion::findOrFail($id);
        $donacion->delete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Donación eliminada correctamente.'], 200);
        }

        return redirect()->route('donaciones.index')->with('success', 'Donación eliminada correctamente.');
    }

    // trae los eliminados logicamente de 5 en 5
    public function trashed(Request $request)
    {
        $donaciones = Donacion::onlyTrashed()->paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['donaciones' => $donaciones], 200);
        }

        return view('donaciones.trashed', compact('donaciones'));
    }

    // regresa la donacion a la tabla
    public function restore(Request $request, $id)
    {
        $donacion = Donacion::onlyTrashed()->findOrFail($id);
        $donacion->restore();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Donación restaurada correctamente.'], 200);
        }

        return redirect()->route('donaciones.trashed')->with('success', 'Donación restaurada correctamente.');
    }

    // borra el registro de la tabla por completo
    public function forceDestroy(Request $request, $id)
    {
        $donacion = Donacion::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminada lógicamente
        if (! $donacion->trashed()) {
            // en api responde 409
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
            }

            return redirect()->route('donaciones.trashed')->with('error', 'Primero debe aplicar el borrado lógico al registro.');
        }

        // validacion de relaciones: si otra tabla la usa, se cancela
        if ($donacion->detalles()->count() > 0) {
            // en api responde 409
            if ($request->wantsJson()) {
                return response()->json(['error' => 'No se puede eliminar: la donación tiene detalles registrados.'], 409);
            }

            return redirect()->route('donaciones.trashed')->with('error', 'No se puede eliminar: la donación tiene detalles registrados.');
        }

        $donacion->forceDelete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Donación eliminada definitivamente.'], 200);
        }

        return redirect()->route('donaciones.trashed')->with('success', 'Donación eliminada definitivamente.');
    }
}
