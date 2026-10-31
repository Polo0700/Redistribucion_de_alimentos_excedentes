<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Usuario;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    // trae las solicitudes de la base de datos con su usuario de 5 en 5
    public function index(Request $request)
    {
        $solicitudes = Solicitud::with('usuario')->paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['solicitudes' => $solicitudes], 200);
        }

        // retorna la pagina del index de solicitudes
        return view('solicitudes.index', compact('solicitudes'));
    }

    // obtiene los usuarios y los ordena por nombre y los mete a usuarios
    public function create(Request $request)
    {
        $usuarios = Usuario::orderBy('nombre')->get();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['usuarios' => $usuarios], 200);
        }

        // retorna la vista de creacion de solicitudes
        return view('solicitudes.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros
        $validado = $request->validate([
            'id_usuario' => 'required|exists:usuarios,id',
            'fecha_solicitud' => 'required|date',
            'estado' => 'required|string|max:30',
            'direccion_entrega' => 'required|string|max:200',
            'observaciones' => 'nullable|string|max:250',
        ]);

        // crea una solicitud
        Solicitud::create($validado);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Solicitud creada correctamente'], 201);
        }

        // redirige al index y un mensaje de correcto
        return redirect()->route('solicitudes.index')->with('success', 'Solicitud creada correctamente');
    }

    public function edit(Request $request, $id)
    {
        $solicitud = Solicitud::findOrFail($id);

        $usuarios = Usuario::where('estado', true)->get();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['solicitud' => $solicitud, 'usuarios' => $usuarios], 200);
        }

        return view(
            'solicitudes.edit',
            compact('solicitud', 'usuarios')
        );
    }

    public function update(Request $request, $id)
    {
        $solicitud = Solicitud::findOrFail($id);

        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id',
            'fecha_solicitud' => 'required|date',
            'estado' => 'required|max:30',
            'direccion_entrega' => 'required|max:200',
            'observaciones' => 'nullable|max:250',
        ]);

        $solicitud->update([
            'id_usuario' => $request->id_usuario,
            'fecha_solicitud' => $request->fecha_solicitud,
            'estado' => $request->estado,
            'direccion_entrega' => $request->direccion_entrega,
            'observaciones' => $request->observaciones,
        ]);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Solicitud actualizada correctamente.'], 200);
        }

        return redirect()
            ->route('solicitudes.index')
            ->with('success', 'Solicitud actualizada correctamente.');
    }

    // retorna la vista show de la solicitud
    public function show(Request $request, $id)
    {
        $solicitud = Solicitud::with('usuario')->findOrFail($id);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['solicitud' => $solicitud], 200);
        }

        return view('solicitudes.show', compact('solicitud'));
    }

    // borra la solicitud logicamente
    public function destroy(Request $request, $id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $solicitud->delete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Solicitud eliminada correctamente.'], 200);
        }

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud eliminada correctamente.');
    }

    // trae los eliminados logicamente de 5 en 5
    public function trashed(Request $request)
    {
        $solicitudes = Solicitud::onlyTrashed()->paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['solicitudes' => $solicitudes], 200);
        }

        return view('solicitudes.trashed', compact('solicitudes'));
    }

    // regresa la solicitud a la tabla
    public function restore(Request $request, $id)
    {
        $solicitud = Solicitud::onlyTrashed()->findOrFail($id);
        $solicitud->restore();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Solicitud restaurada correctamente.'], 200);
        }

        return redirect()->route('solicitudes.trashed')->with('success', 'Solicitud restaurada correctamente.');
    }

    // borra el registro de la tabla por completo
    public function forceDestroy(Request $request, $id)
    {
        $solicitud = Solicitud::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminada lógicamente
        if (! $solicitud->trashed()) {
            // en api responde 409
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
            }

            return redirect()->route('solicitudes.trashed')->with('error', 'Primero debe aplicar el borrado lógico al registro.');
        }

        // validacion de relaciones: si otra tabla la usa, se cancela
        if ($solicitud->detalles()->count() > 0) {
            // en api responde 409
            if ($request->wantsJson()) {
                return response()->json(['error' => 'No se puede eliminar: la solicitud tiene detalles registrados.'], 409);
            }

            return redirect()->route('solicitudes.trashed')->with('error', 'No se puede eliminar: la solicitud tiene detalles registrados.');
        }

        if ($solicitud->entrega()->count() > 0) {
            // en api responde 409
            if ($request->wantsJson()) {
                return response()->json(['error' => 'No se puede eliminar: la solicitud tiene una entrega asociada.'], 409);
            }

            return redirect()->route('solicitudes.trashed')->with('error', 'No se puede eliminar: la solicitud tiene una entrega asociada.');
        }

        $solicitud->forceDelete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Solicitud eliminada definitivamente.'], 200);
        }

        return redirect()->route('solicitudes.trashed')->with('success', 'Solicitud eliminada definitivamente.');
    }
}
