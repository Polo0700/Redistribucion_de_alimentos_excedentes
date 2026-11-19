<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteSolicitudRequest;
use App\Http\Requests\ClienteSolicitudUpdateRequest;
use App\Http\Resources\SolicitudResource;
use App\Models\CuentaAcceso;
use App\Models\Solicitud;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Gestión de los registros propios del cliente autenticado
 * (Actividad 4.4): las solicitudes creadas bajo su id_usuario.
 *
 * La propiedad de datos se garantiza en dos capas:
 *  - scope en la consulta: where('id_usuario', $cuenta->id_usuario)
 *  - Policy: authorize() compara el dueño del registro (403 Forbidden)
 */
class ClienteSolicitudController extends Controller
{
    /**
     * GET /api/v1/cliente/mis-registros
     * Lista EXCLUSIVA de solicitudes del cliente autenticado (scope BD).
     */
    public function index(Request $request): JsonResponse
    {
        /** @var CuentaAcceso $cuenta */
        $cuenta = $request->user();

        $solicitudes = Solicitud::where('id_usuario', $cuenta->id_usuario)
            ->with('detalles.alimento')
            ->latest('fecha_solicitud')
            ->paginate(5);

        return response()->json([
            'solicitudes' => SolicitudResource::collection($solicitudes)->response()->getData(true),
        ], 200);
    }

    /**
     * POST /api/v1/cliente/mis-registros
     * Crea un registro vinculado automáticamente al id_usuario del token.
     * El cliente NO puede inyectar el id del propietario en el payload.
     */
    public function store(ClienteSolicitudRequest $request): JsonResponse
    {
        $validado = $request->validated();

        /** @var CuentaAcceso $cuenta */
        $cuenta = $request->user();

        $solicitud = Solicitud::create([
            'id_usuario' => $cuenta->id_usuario, // asignación automática (auth)
            'direccion_entrega' => $validado['direccion_entrega'],
            'observaciones' => $validado['observaciones'] ?? null,
            'fecha_solicitud' => now(),
            'estado' => 'pendiente',
        ]);

        $solicitud->detalles()->create([
            'id_alimento' => $validado['id_alimento'],
            'cantidad' => $validado['cantidad'],
            'estado' => 'pendiente',
        ]);

        return response()->json([
            'message' => 'Solicitud registrada correctamente.',
            'solicitud' => new SolicitudResource($solicitud->load('detalles.alimento')),
        ], 201);
    }

    /**
     * GET /api/v1/cliente/mis-registros/{id}
     * Detalle de un registro propio: 404 si no existe,
     * 403 si pertenece a otro cliente (Policy view).
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $solicitud = Solicitud::with('detalles.alimento')->findOrFail($id);

        $this->authorize('view', $solicitud);

        return response()->json([
            'solicitud' => new SolicitudResource($solicitud),
        ], 200);
    }

    /**
     * PUT /api/v1/cliente/mis-registros/{id}
     * Modifica los campos editables de un registro propio:
     * 404 si no existe, 403 si pertenece a otro cliente (Policy update).
     */
    public function update(ClienteSolicitudUpdateRequest $request, int $id): JsonResponse
    {
        $solicitud = Solicitud::findOrFail($id);

        $this->authorize('update', $solicitud);

        $solicitud->update($request->validated());

        return response()->json([
            'message' => 'Solicitud actualizada correctamente.',
            'solicitud' => new SolicitudResource($solicitud->fresh()->load('detalles.alimento')),
        ], 200);
    }

    /**
     * DELETE /api/v1/cliente/mis-registros/{id}
     * Cancela un registro propio: la Policy cancelar exige que sea
     * dueño Y que el estado sea cancelable (403 en caso contrario).
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $solicitud = Solicitud::findOrFail($id);

        $this->authorize('cancelar', $solicitud);

        $solicitud->update(['estado' => 'cancelada']);
        $solicitud->detalles()->update(['estado' => 'cancelada']);

        return response()->json([
            'message' => 'Solicitud cancelada correctamente.',
            'solicitud' => new SolicitudResource($solicitud->fresh()->load('detalles.alimento')),
        ], 200);
    }
}
