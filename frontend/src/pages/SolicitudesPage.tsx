import { PageShell } from "../layout/PageShell";
import type { Campo } from "../types/campo";
import { getRecursos, getRecursosById } from "../services/solicitudes";
import type { Solicitud } from "../types/solicitud";
import { useState, useEffect, useRef } from "react";
import { PanelLateral } from "../components/PanelLateral";

// Fuente: app/Http/Resources/SolicitudResource.php
// Ojo: 'estado' NO viene casteado a booleano, llega crudo (texto/enum).
const CAMPOS: Campo[] = [
    { name: "id_solicitud", type: "pk", hint: "Identificador de la solicitud" },
    { name: "id_usuario", type: "fk", hint: "Usuario que solicita" },
    { name: "fecha_solicitud", type: "fecha", hint: "Fecha de la peticion" },
    { name: "estado", type: "texto", hint: "Crudo, sin castear a booleano" },
    { name: "direccion_entrega", type: "texto", hint: "Donde se entrega" },
    { name: "observaciones", type: "texto", hint: "Notas adicionales" },
    { name: "usuario", type: "relacion", hint: "Objeto usuario anidado" },
    { name: "detalles", type: "relacion", hint: "Lista de alimentos con cantidad" },
];

export default function SolicitudesPage() {
    const [solicitudes, setSolicitudes] = useState<Solicitud[]>([]);
    const [estado, setEstado] = useState<
        "cargando" | "listo" | "error" | "vacio"
    >("cargando");
    const [seleccionado, setSeleccionado] = useState<Solicitud | null>(null);
    const [detalle, setDetalle] = useState<Solicitud | null>(null);
    const [estadoDetalle, setEstadoDetalle] = useState<
        "cargando" | "listo" | "error"
    >("cargando");

    // Cada clic invalida la respuesta anterior, aunque llegue mas tarde.
    const peticionActual = useRef(0);

    // Abre el panel al instante y despues pide el detalle a la API.
    function abrir(solicitud: Solicitud) {
        const numero = ++peticionActual.current;
        setSeleccionado(solicitud);
        setDetalle(null);
        setEstadoDetalle("cargando");
        getRecursosById(solicitud.id_solicitud)
            .then((data) => {
                if (numero !== peticionActual.current) {
                    return;
                }
                setDetalle(data);
                setEstadoDetalle(data ? "listo" : "error");
            })
            .catch(() => {
                if (numero !== peticionActual.current) {
                    return;
                }
                setEstadoDetalle("error");
            });
    }

    useEffect(() => {
        getRecursos()
            .then((data) => {
                setSolicitudes(data);
                setEstado(data.length === 0 ? "vacio" : "listo");
            })
            .catch(() => {
                setEstado("error");
            });
    }, []);

    return (
        <PageShell
            title="Solicitudes"
            endpoint="solicitudes"
            vista="Transaccion con relaciones"
            relaciones={["usuario", "detalles"]}
            campos={CAMPOS}
        >
            {estado === "cargando" && (
                <p className="mt-4 text-sm text-gray-500">Cargando...</p>
            )}
            {estado === "error" && (
                <p className="mt-4 text-sm text-red-500">
                    Error al cargar las solicitudes.
                </p>
            )}
            {estado === "vacio" && (
                <p className="mt-4 text-sm text-gray-500">
                    No hay solicitudes disponibles.
                </p>
            )}
            {estado === "listo" && (
                <ul className="mt-4 space-y-2">
                    {solicitudes.map((solicitud) => (
                        <li
                            key={solicitud.id_solicitud}
                            onClick={() => abrir(solicitud)}
                            className="flex cursor-pointer items-center gap-4 rounded-lg border border-gray-200 p-4"
                        >
                            <div>
                                <p className="text-sm font-medium text-gray-900">
                                    Solicitud #{solicitud.id_solicitud}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Estado: {solicitud.estado}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Usuario: {solicitud.usuario?.nombre}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Entrega: {solicitud.direccion_entrega}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Fecha: {solicitud.fecha_solicitud}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Observaciones: {solicitud.observaciones}
                                </p>
                            </div>
                        </li>
                    ))}
                </ul>
            )}
            {seleccionado && (
                <PanelLateral
                    titulo={`Solicitud #${seleccionado.id_solicitud}`}
                    onCerrar={() => setSeleccionado(null)}
                >
                    {estadoDetalle === "cargando" && (
                        <p className="text-sm text-gray-500">
                            Cargando detalle...
                        </p>
                    )}
                    {estadoDetalle === "error" && (
                        <p className="text-sm text-red-500">
                            Error al cargar el detalle.
                        </p>
                    )}
                    {estadoDetalle === "listo" && detalle && (
                        <div>
                            <p className="text-sm text-gray-500">
                                Estado: {detalle.estado}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Usuario: {detalle.usuario?.nombre}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Entrega: {detalle.direccion_entrega}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Fecha: {detalle.fecha_solicitud}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Observaciones: {detalle.observaciones}
                            </p>
                            {detalle.detalles && (
                                <p className="mt-2 text-sm text-gray-500">
                                    Detalles: {detalle.detalles.length}
                                </p>
                            )}
                        </div>
                    )}
                </PanelLateral>
            )}
        </PageShell>
    );
}
