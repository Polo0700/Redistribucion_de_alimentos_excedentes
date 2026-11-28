import { PageShell } from "../layout/PageShell";
import type { Campo } from "../types/campo";
import { getRecursos, getRecursosById } from "../services/roles";
import type { Rol } from "../types/rol";
import { useState, useEffect, useRef } from "react";
import { PanelLateral } from "../components/PanelLateral";
import { errorMessage } from "../services/errores";
import { Spinner } from "../components/Spinner";

// Fuente: app/Http/Resources/RolResource.php
const CAMPOS: Campo[] = [
    { name: "id", type: "pk", hint: "Identificador del rol" },
    { name: "nombre", type: "texto", hint: "Nombre del rol" },
    { name: "descripcion", type: "texto", hint: "Que hace este rol" },
    { name: "estado", type: "booleano", hint: "true = activo, false = inactivo" },
];

export default function RolesPage() {
    const [roles, setRoles] = useState<Rol[]>([]);
    const [estado, setEstado] = useState<
        "cargando" | "listo" | "error" | "vacio"
    >("cargando");
    const [seleccionado, setSeleccionado] = useState<Rol | null>(null);
    const [detalle, setDetalle] = useState<Rol | null>(null);
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
    function abrir(rol: Rol) {
        const numero = ++peticionActual.current;
        setSeleccionado(rol);
        setDetalle(null);
        setEstadoDetalle("cargando");
        getRecursosById(rol.id)
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
                setRoles(data);
                setEstado(data.length === 0 ? "vacio" : "listo");
            })
            .catch((error) => {
                setMensajeError(errorMessage(error));
                setEstado("error");
            });
    }, []);

    return (
        <PageShell
            title="Roles"
            endpoint="roles"
            vista="Catalogo simple"
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
                    No hay roles disponibles.
                </p>
            )}
            {estado === "listo" && (
                <ul className="mt-4 space-y-2">
                    {roles.map((rol) => (
                        <li
                            key={rol.id}
                            onClick={() => abrir(rol)}
                            className="flex cursor-pointer items-center gap-4 rounded-lg border border-gray-200 p-4"
                        >
                            <div>
                                <p className="text-sm font-medium text-gray-900">
                                    {rol.nombre}
                                </p>
                                <p className="text-sm text-gray-500">
                                    ID: {rol.id}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Descripcion: {rol.descripcion}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Estado: {rol.estado ? "Activo" : "Inactivo"}
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
                            <p className="text-sm text-gray-500">
                                ID: {detalle.id}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Descripcion: {detalle.descripcion}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Estado:{" "}
                                {detalle.estado ? "Activo" : "Inactivo"}
                            </p>
                        </div>
                    )}
                </PanelLateral>
            )}
        </PageShell>
    );
}
