import { PageShell } from "../layout/PageShell";
import type { Campo } from "../types/campo";
import { getRecursos, getRecursosById } from "../services/acciones";
import type { AccionImportante } from "../types/accion";
import { useState, useEffect, useRef } from "react";
import { PanelLateral } from "../components/PanelLateral";

// Fuente: app/Http/Resources/AccionImportanteResource.php
// Vista de solo lectura: es un log de auditoria, no tiene CRUD.
const CAMPOS: Campo[] = [
    { name: "id_accion", type: "pk", hint: "Identificador del registro" },
    { name: "id_usuario", type: "fk", hint: "Usuario que ejecuto la accion" },
    { name: "accion", type: "texto", hint: "Que se hizo (crear, eliminar...)" },
    { name: "tabla_afectada", type: "texto", hint: "Tabla sobre la que se opero" },
    { name: "descripcion", type: "texto", hint: "Detalle legible de la accion" },
    { name: "fecha_hora", type: "datetime", hint: "Momento exacto de la accion" },
    { name: "ip_origen", type: "texto", hint: "IP desde la que se ejecuto" },
    { name: "usuario", type: "relacion", hint: "Objeto usuario anidado" },
];

export default function AccionesPage() {
    const [acciones, setAcciones] = useState<AccionImportante[]>([]);
    const [estado, setEstado] = useState<
        "cargando" | "listo" | "error" | "vacio"
    >("cargando");
    const [seleccionado, setSeleccionado] = useState<AccionImportante | null>(
        null,
    );
    const [detalle, setDetalle] = useState<AccionImportante | null>(null);
    const [estadoDetalle, setEstadoDetalle] = useState<
        "cargando" | "listo" | "error"
    >("cargando");

    // Cada clic invalida la respuesta anterior, aunque llegue mas tarde.
    const peticionActual = useRef(0);

    // Abre el panel al instante y despues pide el detalle a la API.
    function abrir(accion: AccionImportante) {
        const numero = ++peticionActual.current;
        setSeleccionado(accion);
        setDetalle(null);
        setEstadoDetalle("cargando");
        getRecursosById(accion.id_accion)
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
                setAcciones(data);
                setEstado(data.length === 0 ? "vacio" : "listo");
            })
            .catch(() => {
                setEstado("error");
            });
    }, []);

    return (
        <PageShell
            title="Acciones importantes"
            endpoint="acciones-importantes"
            vista="Log de auditoria (solo lectura)"
            relaciones={["usuario"]}
            campos={CAMPOS}
        >
            {estado === "cargando" && (
                <p className="mt-4 text-sm text-gray-500">Cargando...</p>
            )}
            {estado === "error" && (
                <p className="mt-4 text-sm text-red-500">
                    Error al cargar las acciones importantes.
                </p>
            )}
            {estado === "vacio" && (
                <p className="mt-4 text-sm text-gray-500">
                    No hay acciones registradas.
                </p>
            )}
            {estado === "listo" && (
                <ul className="mt-4 space-y-2">
                    {acciones.map((accion) => (
                        <li
                            key={accion.id_accion}
                            onClick={() => abrir(accion)}
                            className="flex cursor-pointer items-center gap-4 rounded-lg border border-gray-200 p-4"
                        >
                            <div>
                                <p className="text-sm font-medium text-gray-900">
                                    {accion.accion}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Tabla: {accion.tabla_afectada}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Descripcion: {accion.descripcion}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Usuario: {accion.usuario?.nombre}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Fecha: {accion.fecha_hora}
                                </p>
                                <p className="text-sm text-gray-500">
                                    IP: {accion.ip_origen}
                                </p>
                            </div>
                        </li>
                    ))}
                </ul>
            )}
            {seleccionado && (
                <PanelLateral
                    titulo={seleccionado.accion}
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
                                Tabla: {detalle.tabla_afectada}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Descripcion: {detalle.descripcion}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Usuario: {detalle.usuario?.nombre}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Fecha: {detalle.fecha_hora}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                IP: {detalle.ip_origen}
                            </p>
                        </div>
                    )}
                </PanelLateral>
            )}
        </PageShell>
    );
}
