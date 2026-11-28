import { PageShell } from "../layout/PageShell";
import type { Campo } from "../types/campo";
import { getRecursos, getRecursosById } from "../services/administradores";
import type { Administrador } from "../types/administrador";
import { IMAGEN_PLACEHOLDER } from "../config";
import { useState, useEffect, useRef } from "react";
import { PanelLateral } from "../components/PanelLateral";
import { errorMessage } from "../services/errores";
import { Spinner } from "../components/Spinner";

// Fuente: app/Http/Resources/AdministradorResource.php
const CAMPOS: Campo[] = [
    { name: "id", type: "pk", hint: "Identificador del administrador" },
    { name: "nombre_completo", type: "texto", hint: "Nombre y apellidos" },
    { name: "username", type: "texto", hint: "Usuario de acceso al panel" },
    { name: "foto_url", type: "imagen", hint: "URL publica de la foto o null" },
    { name: "activo", type: "booleano", hint: "true = puede iniciar sesion" },
    { name: "nivel", type: "texto", hint: "Nivel o jerarquia del administrador" },
    { name: "created_at", type: "datetime", hint: "Alta de la cuenta" },
    { name: "updated_at", type: "datetime", hint: "Ultima modificacion" },
];

export default function AdministradoresPage() {
    const [administradores, setAdministradores] = useState<Administrador[]>([]);
    const [estado, setEstado] = useState<
        "cargando" | "listo" | "error" | "vacio"
    >("cargando");
    const [seleccionado, setSeleccionado] = useState<Administrador | null>(null);
    const [detalle, setDetalle] = useState<Administrador | null>(null);
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
    function abrir(administrador: Administrador) {
        const numero = ++peticionActual.current;
        setSeleccionado(administrador);
        setDetalle(null);
        setEstadoDetalle("cargando");
        getRecursosById(administrador.id)
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
                setAdministradores(data);
                setEstado(data.length === 0 ? "vacio" : "listo");
            })
            .catch((error) => {
                setMensajeError(errorMessage(error));
                setEstado("error");
            });
    }, []);

    return (
        <PageShell
            title="Administradores"
            endpoint="administradores"
            vista="Directorio con foto"
            imagenes
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
                    No hay administradores disponibles.
                </p>
            )}
            {estado === "listo" && (
                <ul className="mt-4 space-y-2">
                    {administradores.map((administrador) => (
                        <li
                            key={administrador.id}
                            onClick={() => abrir(administrador)}
                            className="flex cursor-pointer items-center gap-4 rounded-lg border border-gray-200 p-4"
                        >
                            <img
                                src={
                                    administrador.foto_url ?? IMAGEN_PLACEHOLDER
                                }
                                onError={(e) => {
                                    e.currentTarget.src = IMAGEN_PLACEHOLDER;
                                }}
                                className="h-16 w-16 rounded-full object-cover"
                                alt={administrador.nombre_completo}
                            />
                            <div>
                                <p className="text-sm font-medium text-gray-900">
                                    {administrador.nombre_completo}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Usuario: {administrador.username}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Nivel: {administrador.nivel}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Activo:{" "}
                                    {administrador.activo ? "Si" : "No"}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Alta: {administrador.created_at}
                                </p>
                            </div>
                        </li>
                    ))}
                </ul>
            )}
            {seleccionado && (
                <PanelLateral
                    titulo={seleccionado.nombre_completo}
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
                                src={detalle.foto_url ?? IMAGEN_PLACEHOLDER}
                                onError={(e) => {
                                    e.currentTarget.src = IMAGEN_PLACEHOLDER;
                                }}
                                className="h-40 w-full rounded-lg object-cover"
                                alt={detalle.nombre_completo}
                            />
                            <p className="mt-4 text-sm text-gray-500">
                                ID: {detalle.id}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Usuario: {detalle.username}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Nivel: {detalle.nivel}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Activo: {detalle.activo ? "Si" : "No"}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Alta: {detalle.created_at}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Actualizacion: {detalle.updated_at}
                            </p>
                        </div>
                    )}
                </PanelLateral>
            )}
        </PageShell>
    );
}
