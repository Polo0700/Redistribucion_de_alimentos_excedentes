import { PageShell } from "../layout/PageShell";
import type { Campo } from "../types/campo";
import { getRecursos, getRecursosById } from "../services/cuentasAcceso";
import type { CuentaAcceso } from "../types/cuentaAcceso";
import { useState, useEffect, useRef } from "react";
import { PanelLateral } from "../components/PanelLateral";
import { errorMessage } from "../services/errores";
import { Spinner } from "../components/Spinner";

// Fuente: app/Http/Resources/CuentaAccesoResource.php
const CAMPOS: Campo[] = [
    { name: "id_cuenta", type: "pk", hint: "Identificador de la cuenta" },
    { name: "id_usuario", type: "fk", hint: "Usuario al que pertenece" },
    { name: "proveedor", type: "texto", hint: "Origen del acceso (local, oauth...)" },
    { name: "identificador_externo", type: "texto", hint: "ID en el proveedor externo" },
    { name: "fecha_ultimo_acceso", type: "datetime", hint: "Ultimo inicio de sesion" },
    { name: "usuario", type: "relacion", hint: "Objeto usuario, solo si viene cargado" },
];

export default function CuentasAccesoPage() {
    const [cuentas, setCuentas] = useState<CuentaAcceso[]>([]);
    const [estado, setEstado] = useState<
        "cargando" | "listo" | "error" | "vacio"
    >("cargando");
    const [seleccionado, setSeleccionado] = useState<CuentaAcceso | null>(null);
    const [detalle, setDetalle] = useState<CuentaAcceso | null>(null);
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
    function abrir(cuenta: CuentaAcceso) {
        const numero = ++peticionActual.current;
        setSeleccionado(cuenta);
        setDetalle(null);
        setEstadoDetalle("cargando");
        getRecursosById(cuenta.id_cuenta)
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
                setCuentas(data);
                setEstado(data.length === 0 ? "vacio" : "listo");
            })
            .catch((error) => {
                setMensajeError(errorMessage(error));
                setEstado("error");
            });
    }, []);

    return (
        <PageShell
            title="Cuentas de acceso"
            endpoint="cuentas-acceso"
            vista="Credenciales y accesos"
            relaciones={["usuario"]}
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
                    No hay cuentas disponibles.
                </p>
            )}
            {estado === "listo" && (
                <ul className="mt-4 space-y-2">
                    {cuentas.map((cuenta) => (
                        <li
                            key={cuenta.id_cuenta}
                            onClick={() => abrir(cuenta)}
                            className="flex cursor-pointer items-center gap-4 rounded-lg border border-gray-200 p-4"
                        >
                            <div>
                                <p className="text-sm font-medium text-gray-900">
                                    {cuenta.proveedor}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Usuario: {cuenta.usuario?.nombre}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Identificador externo:{" "}
                                    {cuenta.identificador_externo}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Ultimo acceso: {cuenta.fecha_ultimo_acceso}
                                </p>
                            </div>
                        </li>
                    ))}
                </ul>
            )}
            {seleccionado && (
                <PanelLateral
                    titulo={seleccionado.proveedor}
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
                                ID: {detalle.id_cuenta}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Usuario: {detalle.usuario?.nombre}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Identificador externo:{" "}
                                {detalle.identificador_externo}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Ultimo acceso: {detalle.fecha_ultimo_acceso}
                            </p>
                        </div>
                    )}
                </PanelLateral>
            )}
        </PageShell>
    );
}
