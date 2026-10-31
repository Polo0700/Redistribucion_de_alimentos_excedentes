<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use App\Models\Solicitud;
use Illuminate\Http\Request;

class EntregaController extends Controller
{
    // trae las entregas de la base de datos con su solicitud de 5 en 5
    public function index(Request $request)
    {
        $entregas = Entrega::with('solicitud')->paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['entregas' => $entregas], 200);
        }

        // retorna la pagina del index de entregas
        return view('entregas.index', compact('entregas'));
    }

    // obtiene las solicitudes y las organiza por id y las mete a solicitudes
    public function create(Request $request)
    {
        $solicitudes = Solicitud::orderBy('id_solicitud')->get();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['solicitudes' => $solicitudes], 200);
        }

        // retorna la vista de creacion de entregas
        return view('entregas.create', compact('solicitudes'));
    }

    public function store(Request $request)
    {
        // toma la peticion y la comprueba si tiene los parametros
        $validado = $request->validate([
            'id_solicitud' => 'required|exists:solicitudes,id_solicitud',
            'fecha_entrega' => 'required|date',
            'responsable' => 'required|string|max:100',
            'estado' => 'required|string|max:30',
            'observaciones' => 'nullable|string|max:250',
        ]);

        // crea una entrega
        Entrega::create($validado);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Entrega creada correctamente'], 201);
        }

        // redirige al index y un mensaje de correcto
        return redirect()->route('entregas.index')->with('success', 'Entrega creada correctamente');
    }

    public function edit(Request $request, $id)
    {
        $entrega = Entrega::findOrFail($id);

        $solicitudes = Solicitud::with('usuario')->get();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['entrega' => $entrega, 'solicitudes' => $solicitudes], 200);
        }

        return view(
            'entregas.edit',
            compact('entrega', 'solicitudes')
        );
    }

    public function update(Request $request, $id)
    {
        $entrega = Entrega::findOrFail($id);

        $request->validate([
            'id_solicitud' => 'required|exists:solicitudes,id_solicitud',
            'fecha_entrega' => 'required|date',
            'responsable' => 'required|max:100',
            'estado' => 'required|max:30',
            'observaciones' => 'nullable|max:250',
        ]);

        $entrega->update([
            'id_solicitud' => $request->id_solicitud,
            'fecha_entrega' => $request->fecha_entrega,
            'responsable' => $request->responsable,
            'estado' => $request->estado,
            'observaciones' => $request->observaciones,
        ]);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Entrega actualizada correctamente.'], 200);
        }

        return redirect()
            ->route('entregas.index')
            ->with('success', 'Entrega actualizada correctamente.');
    }

    // retorna la vista show de la entrega
    public function show(Request $request, $id)
    {
        $entrega = Entrega::with('solicitud')->findOrFail($id);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['entrega' => $entrega], 200);
        }

        return view('entregas.show', compact('entrega'));
    }

    // borra la entrega logicamente
    public function destroy(Request $request, $id)
    {
        $entrega = Entrega::findOrFail($id);
        $entrega->delete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Entrega eliminada correctamente.'], 200);
        }

        return redirect()->route('entregas.index')->with('success', 'Entrega eliminada correctamente.');
    }

    // trae los eliminados logicamente de 5 en 5
    public function trashed(Request $request)
    {
        $entregas = Entrega::onlyTrashed()->paginate(5);

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['entregas' => $entregas], 200);
        }

        return view('entregas.trashed', compact('entregas'));
    }

    // regresa la entrega a la tabla
    public function restore(Request $request, $id)
    {
        $entrega = Entrega::onlyTrashed()->findOrFail($id);
        $entrega->restore();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Entrega restaurada correctamente.'], 200);
        }

        return redirect()->route('entregas.trashed')->with('success', 'Entrega restaurada correctamente.');
    }

    // borra el registro de la tabla por completo
    public function forceDestroy(Request $request, $id)
    {
        $entrega = Entrega::withTrashed()->findOrFail($id);

        // solo se permite si ya estaba eliminada lógicamente
        if (! $entrega->trashed()) {
            // en api responde 409
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Primero debe aplicar el borrado lógico al registro.'], 409);
            }

            return redirect()->route('entregas.trashed')->with('error', 'Primero debe aplicar el borrado lógico al registro.');
        }

        // sin tablas dependientes: se elimina directamente
        $entrega->forceDelete();

        // en api devuelve json
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Entrega eliminada definitivamente.'], 200);
        }

        return redirect()->route('entregas.trashed')->with('success', 'Entrega eliminada definitivamente.');
    }
}
