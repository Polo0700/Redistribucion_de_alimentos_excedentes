import { PageShell } from "../layout/PageShell";
import type { Campo } from "../types/campo";
import { getRecursos, getRecursosById } from "../services/usuarios";
import type { Usuario } from "../types/usuario";
import { useState, useEffect, useRef } from "react";
import { PanelLateral } from "../components/PanelLateral";
import { errorMessage } from "../services/errores";
import { Spinner } from "../components/Spinner";

// Fuente: app/Http/Resources/UsuarioResource.php
const CAMPOS: Campo[] = [
    { name: "id", type: "pk", hint: "Identificador del usuario" },
    { name: "id_rol", type: "fk", hint: "Rol asignado" },
    { name: "nombre", type: "texto", hint: "Nombre" },
    { name: "apellido", type: "texto", hint: "Apellido" },
    { name: "correo", type: "texto", hint: "Correo electronico" },
    { name: "telefono", type: "texto", hint: "Telefono de contacto" },
    { name: "direccion", type: "texto", hint: "Direccion fisica" },
    { name: "fecha_registro", type: "fecha", hint: "Alta en el sistema" },
    { name: "estado", type: "booleano", hint: "true = activo, false = inactivo" },
    { name: "rol", type: "relacion", hint: "Objeto rol, solo si viene cargado" },
];

export default function UsuariosPage() {
    const [usuarios, setUsuarios] = useState<Usuario[]>([]);
    const [estado, setEstado] = useState<
        "cargando" | "listo" | "error" | "vacio"
    >("cargando");
    const [seleccionado, setSeleccionado] = useState<Usuario | null>(null);
    const [detalle, setDetalle] = useState<Usuario | null>(null);
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
    function abrir(usuario: Usuario) {
        const numero = ++peticionActual.current;
        setSeleccionado(usuario);
        setDetalle(null);
        setEstadoDetalle("cargando");
        getRecursosById(usuario.id)
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
                setUsuarios(data);
                setEstado(data.length === 0 ? "vacio" : "listo");
            })
            .catch((error) => {
                setMensajeError(errorMessage(error));
                setEstado("error");
            });
    }, []);

    return (
        <PageShell
            title="Usuarios"
            endpoint="usuarios"
            vista="Directorio de personas"
            relaciones={["rol"]}
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
                    No hay usuarios disponibles.
                </p>
            )}
            {estado === "listo" && (
                <ul className="mt-4 space-y-2">
                    {usuarios.map((usuario) => (
                        <li
                            key={usuario.id}
                            onClick={() => abrir(usuario)}
                            className="flex cursor-pointer items-center gap-4 rounded-lg border border-gray-200 p-4"
                        >
                            <div>
                                <p className="text-sm font-medium text-gray-900">
                                    {usuario.nombre} {usuario.apellido}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Correo: {usuario.correo}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Rol: {usuario.rol?.nombre}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Estado:{" "}
                                    {usuario.estado ? "Activo" : "Inactivo"}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Registro: {usuario.fecha_registro}
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
                                Nombre completo: {detalle.nombre}{" "}
                                {detalle.apellido}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Correo: {detalle.correo}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Rol: {detalle.rol?.nombre}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Telefono: {detalle.telefono}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Direccion: {detalle.direccion}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Registro: {detalle.fecha_registro}
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
