import { PageShell } from "../layout/PageShell";
import type { Campo } from "../types/campo";
import { getRecursos, getRecursosById } from "../services/entregas";
import type { Entrega } from "../types/entrega";
import { useState, useEffect, useRef } from "react";
import { PanelLateral } from "../components/PanelLateral";

// Fuente: app/Http/Resources/EntregaResource.php
// Ojo: 'estado' NO viene casteado a booleano, llega crudo (texto/enum).
const CAMPOS: Campo[] = [
    { name: "id_entrega", type: "pk", hint: "Identificador de la entrega" },
    { name: "id_solicitud", type: "fk", hint: "Solicitud que se entrega" },
    { name: "fecha_entrega", type: "fecha", hint: "Fecha efectiva de entrega" },
    { name: "responsable", type: "texto", hint: "Quien entrega" },
    { name: "estado", type: "texto", hint: "Crudo, sin castear a booleano" },
    { name: "observaciones", type: "texto", hint: "Notas de la entrega" },
    { name: "solicitud", type: "relacion", hint: "Objeto solicitud anidado" },
];

export default function EntregasPage() {
    const [entregas, setEntregas] = useState<Entrega[]>([]);
    const [estado, setEstado] = useState<
        "cargando" | "listo" | "error" | "vacio"
    >("cargando");
    const [seleccionado, setSeleccionado] = useState<Entrega | null>(null);
    const [detalle, setDetalle] = useState<Entrega | null>(null);
    const [estadoDetalle, setEstadoDetalle] = useState<
        "cargando" | "listo" | "error"
    >("cargando");

    // Cada clic invalida la respuesta anterior, aunque llegue mas tarde.
    const peticionActual = useRef(0);

    // Abre el panel al instante y despues pide el detalle a la API.
    function abrir(entrega: Entrega) {
        const numero = ++peticionActual.current;
        setSeleccionado(entrega);
        setDetalle(null);
        setEstadoDetalle("cargando");
        getRecursosById(entrega.id_entrega)
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
                setEntregas(data);
                setEstado(data.length === 0 ? "vacio" : "listo");
            })
            .catch(() => {
                setEstado("error");
            });
    }, []);

    return (
        <PageShell
            title="Entregas"
            endpoint="entregas"
            vista="Logistica"
            relaciones={["solicitud"]}
            campos={CAMPOS}
        >
            {estado === "cargando" && (
                <p className="mt-4 text-sm text-gray-500">Cargando...</p>
            )}
            {estado === "error" && (
                <p className="mt-4 text-sm text-red-500">
                    Error al cargar las entregas.
                </p>
            )}
            {estado === "vacio" && (
                <p className="mt-4 text-sm text-gray-500">
                    No hay entregas disponibles.
                </p>
            )}
            {estado === "listo" && (
                <ul className="mt-4 space-y-2">
                    {entregas.map((entrega) => (
                        <li
                            key={entrega.id_entrega}
                            onClick={() => abrir(entrega)}
                            className="flex cursor-pointer items-center gap-4 rounded-lg border border-gray-200 p-4"
                        >
                            <div>
                                <p className="text-sm font-medium text-gray-900">
                                    Entrega #{entrega.id_entrega}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Estado: {entrega.estado}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Responsable: {entrega.responsable}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Solicitud: #{entrega.id_solicitud}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Fecha: {entrega.fecha_entrega}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Observaciones: {entrega.observaciones}
                                </p>
                            </div>
                        </li>
                    ))}
                </ul>
            )}
            {seleccionado && (
                <PanelLateral
                    titulo={`Entrega #${seleccionado.id_entrega}`}
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
                                Responsable: {detalle.responsable}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Solicitud: #{detalle.id_solicitud}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Fecha: {detalle.fecha_entrega}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Observaciones: {detalle.observaciones}
                            </p>
                        </div>
                    )}
                </PanelLateral>
            )}
        </PageShell>
    );
}
