import { PageShell } from "../layout/PageShell";
import type { Campo } from "../types/campo";
import { getRecursos, getRecursosById } from "../services/donaciones";
import type { Donacion } from "../types/donacion";
import { useState, useEffect, useRef } from "react";
import { PanelLateral } from "../components/PanelLateral";

// Fuente: app/Http/Resources/DonacionResource.php
// Ojo: 'estado' NO viene casteado a booleano, llega crudo (texto/enum).
const CAMPOS: Campo[] = [
    { name: "id_donacion", type: "pk", hint: "Identificador de la donacion" },
    { name: "id_usuario", type: "fk", hint: "Usuario que dona" },
    { name: "fecha_donacion", type: "fecha", hint: "Fecha de la donacion" },
    { name: "fecha_limite", type: "fecha", hint: "Fecha limite de entrega" },
    { name: "ubicacion", type: "texto", hint: "Donde se recoge" },
    { name: "estado", type: "texto", hint: "Crudo, sin castear a booleano" },
    { name: "observaciones", type: "texto", hint: "Notas adicionales" },
    { name: "usuario", type: "relacion", hint: "Objeto usuario anidado" },
    { name: "detalles", type: "relacion", hint: "Lista de alimentos con cantidad" },
];

export default function DonacionesPage() {
    const [donaciones, setDonaciones] = useState<Donacion[]>([]);
    const [estado, setEstado] = useState<
        "cargando" | "listo" | "error" | "vacio"
    >("cargando");
    const [seleccionado, setSeleccionado] = useState<Donacion | null>(null);
    const [detalle, setDetalle] = useState<Donacion | null>(null);
    const [estadoDetalle, setEstadoDetalle] = useState<
        "cargando" | "listo" | "error"
    >("cargando");

    // Cada clic invalida la respuesta anterior, aunque llegue mas tarde.
    const peticionActual = useRef(0);

    // Abre el panel al instante y despues pide el detalle a la API.
    function abrir(donacion: Donacion) {
        const numero = ++peticionActual.current;
        setSeleccionado(donacion);
        setDetalle(null);
        setEstadoDetalle("cargando");
        getRecursosById(donacion.id_donacion)
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
                setDonaciones(data);
                setEstado(data.length === 0 ? "vacio" : "listo");
            })
            .catch(() => {
                setEstado("error");
            });
    }, []);

    return (
        <PageShell
            title="Donaciones"
            endpoint="donaciones"
            vista="Transaccion con relaciones"
            relaciones={["usuario", "detalles"]}
            campos={CAMPOS}
        >
            {estado === "cargando" && (
                <p className="mt-4 text-sm text-gray-500">Cargando...</p>
            )}
            {estado === "error" && (
                <p className="mt-4 text-sm text-red-500">
                    Error al cargar las donaciones.
                </p>
            )}
            {estado === "vacio" && (
                <p className="mt-4 text-sm text-gray-500">
                    No hay donaciones disponibles.
                </p>
            )}
            {estado === "listo" && (
                <ul className="mt-4 space-y-2">
                    {donaciones.map((donacion) => (
                        <li
                            key={donacion.id_donacion}
                            onClick={() => abrir(donacion)}
                            className="flex cursor-pointer items-center gap-4 rounded-lg border border-gray-200 p-4"
                        >
                            <div>
                                <p className="text-sm font-medium text-gray-900">
                                    Donacion #{donacion.id_donacion}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Estado: {donacion.estado}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Usuario: {donacion.usuario?.nombre}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Ubicacion: {donacion.ubicacion}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Fecha: {donacion.fecha_donacion}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Limite: {donacion.fecha_limite}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Observaciones: {donacion.observaciones}
                                </p>
                            </div>
                        </li>
                    ))}
                </ul>
            )}
            {seleccionado && (
                <PanelLateral
                    titulo={`Donacion #${seleccionado.id_donacion}`}
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
                                Ubicacion: {detalle.ubicacion}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Fecha: {detalle.fecha_donacion}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Limite: {detalle.fecha_limite}
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
