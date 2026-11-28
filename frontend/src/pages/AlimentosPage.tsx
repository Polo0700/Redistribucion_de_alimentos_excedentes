import { PageShell } from "../layout/PageShell";
import type { Campo } from "../types/campo";
import { getRecursos, getRecursosById } from "../services/alimentos";
import type { Alimento } from "../types/alimento";
import { useState, useEffect, useRef } from "react";
import { IMAGEN_PLACEHOLDER } from "../config";
import { PanelLateral } from "../components/PanelLateral";
import { errorMessage } from "../services/errores";
import { Spinner } from "../components/Spinner";

// Fuente: app/Http/Resources/AlimentoResource.php
const CAMPOS: Campo[] = [
    { name: "id_alimento", type: "pk", hint: "Identificador del alimento" },
    { name: "id_categoria", type: "fk", hint: "Categoria a la que pertenece" },
    { name: "nombre", type: "texto", hint: "Nombre del alimento" },
    {
        name: "descripcion",
        type: "texto",
        hint: "Descripcion larga, puede venir vacia",
    },
    {
        name: "estado",
        type: "booleano",
        hint: "true = activo, false = inactivo",
    },
    {
        name: "imagen_url",
        type: "imagen",
        hint: "URL publica o null si no tiene imagen",
    },
    {
        name: "categoria",
        type: "relacion",
        hint: "Objeto categoria, solo si viene cargada",
    },
];

export default function AlimentosPage() {
    const [alimentos, setAlimentos] = useState<Alimento[]>([]);
    const [estado, setEstado] = useState<
        "cargando" | "listo" | "error" | "vacio"
    >("cargando");
    const [seleccionado, setSeleccionado] = useState<Alimento | null>(null);
    const [detalle, setDetalle] = useState<Alimento | null>(null);
    const [estadoDetalle, setEstadoDetalle] = useState<
        "cargando" | "listo" | "error"
    >("cargando");
    const [mensajeError, setMensajeError] = useState<string | null>(null);
    const [mensajeErrorDetalle, setMensajeErrorDetalle] = useState<
        string | null
    >(null);

    // Cada clic invalida la respuesta anterior, aunque llegue mas tarde.
    const peticionActual = useRef(0);

    // Abre el panel al instante y despues pide el detalle a la API.
    function abrir(alimento: Alimento) {
        const numero = ++peticionActual.current;
        setSeleccionado(alimento);
        setDetalle(null);
        setEstadoDetalle("cargando");
        getRecursosById(alimento.id_alimento)
            .then((data) => {
                if (numero !== peticionActual.current) {
                    return;
                }
                if (!data) {
                    setMensajeErrorDetalle("Recurso no encontrado (404).");
                    setEstadoDetalle("error");
                    return;
                }
                setDetalle(data);
                setEstadoDetalle("listo");
            })
            .catch((error) => {
                if (numero !== peticionActual.current) {
                    return;
                }
                setMensajeErrorDetalle(errorMessage(error));
                setEstadoDetalle("error");
            });
    }

    useEffect(() => {
        getRecursos()
            .then((data) => {
                setAlimentos(data);
                if (data.length === 0) {
                    setEstado("vacio");
                } else {
                    setEstado("listo");
                }
            })
            .catch((error) => {
                setMensajeError(errorMessage(error));
                setEstado("error");
            });
    }, []);
    return (
        <PageShell
            title="Alimentos"
            endpoint="alimentos"
            vista="Catalogo con imagen"
            imagenes
            relaciones={["categoria"]}
            campos={CAMPOS}
        >
            {estado === "cargando" && <Spinner />}
            {estado === "error" && (
                <p className="mt-4 text-sm text-red-500">
                    {mensajeError ?? "Error al cargar los datos."}
                </p>
            )}
            {estado === "vacio" && (
                <p className="mt-4 text-sm text-gray-500">
                    No hay alimentos disponibles.
                </p>
            )}
            {estado === "listo" && (
                <ul className="mt-4 space-y-2">
                    {alimentos.map((alimento) => (
                        <li
                            key={alimento.id_alimento}
                            onClick={() => abrir(alimento)}
                            className="flex cursor-pointer items-center gap-4 rounded-lg border border-gray-200 p-4"
                        >
                            <img
                                src={alimento.imagen_url ?? IMAGEN_PLACEHOLDER}
                                onError={(e) => {
                                    e.currentTarget.src = IMAGEN_PLACEHOLDER;
                                }}
                                className="h-16 w-16 rounded-full object-cover"
                                alt={alimento.nombre}
                            />
                            <div>
                                <p className="text-sm font-medium text-gray-900">
                                    {alimento.nombre}
                                </p>
                                <p className="text-sm text-gray-500">
                                    categoria: {alimento.categoria?.nombre}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Estado:{" "}
                                    {alimento.estado ? "Activo" : "Inactivo"}
                                </p>
                                <p className="text-sm text-gray-500">
                                    {alimento.descripcion}
                                </p>
                            </div>
                        </li>
                    ))}
                </ul>
            )}
            {seleccionado && (
                <PanelLateral
                    titulo={seleccionado.nombre}
                    onCerrar={() => setSeleccionado(null)}
                >
                    {estadoDetalle === "cargando" && (
                        <Spinner texto="Cargando detalle..." />
                    )}
                    {estadoDetalle === "error" && (
                        <p className="text-sm text-red-500">
                            {mensajeErrorDetalle ??
                                "Error al cargar el detalle."}
                        </p>
                    )}
                    {estadoDetalle === "listo" && detalle && (
                        <div>
                            <img
                                src={detalle.imagen_url ?? IMAGEN_PLACEHOLDER}
                                onError={(e) => {
                                    e.currentTarget.src = IMAGEN_PLACEHOLDER;
                                }}
                                className="h-40 w-full rounded-lg object-cover"
                                alt={detalle.nombre}
                            />
                            <p className="mt-4 text-sm text-gray-500">
                                ID: {detalle.id_alimento}
                            </p>
                            <p className="text-sm text-gray-500">
                                Categoria: {detalle.categoria?.nombre}
                            </p>
                            <p className="text-sm text-gray-500">
                                Estado:{" "}
                                {detalle.estado ? "Activo" : "Inactivo"}
                            </p>
                            <p className="mt-2 text-sm text-gray-600">
                                {detalle.descripcion}
                            </p>
                            <p className="mt-2 break-all text-xs text-gray-400">
                                {detalle.imagen_url}
                            </p>
                        </div>
                    )}
                </PanelLateral>
            )}
        </PageShell>
    );
}
