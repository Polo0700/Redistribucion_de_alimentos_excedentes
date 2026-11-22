import { PageShell } from "../layout/PageShell";
import type { Campo } from "../types/campo";
import { getRecursos, getRecursosById } from "../services/categorias";
import type { CategoriaAlimento } from "../types/categoria";
import { useState, useEffect, useRef } from "react";
import { PanelLateral } from "../components/PanelLateral";

// Fuente: app/Http/Resources/CategoriaAlimentoResource.php
const CAMPOS: Campo[] = [
    { name: "id_categoria", type: "pk", hint: "Identificador de la categoria" },
    { name: "nombre", type: "texto", hint: "Nombre de la categoria" },
    { name: "descripcion", type: "texto", hint: "Descripcion, puede venir vacia" },
    { name: "estado", type: "booleano", hint: "true = activa, false = inactiva" },
];

export default function CategoriasPage() {
    const [categorias, setCategorias] = useState<CategoriaAlimento[]>([]);
    const [estado, setEstado] = useState<
        "cargando" | "listo" | "error" | "vacio"
    >("cargando");
    const [seleccionado, setSeleccionado] = useState<CategoriaAlimento | null>(
        null,
    );
    const [detalle, setDetalle] = useState<CategoriaAlimento | null>(null);
    const [estadoDetalle, setEstadoDetalle] = useState<
        "cargando" | "listo" | "error"
    >("cargando");

    // Cada clic invalida la respuesta anterior, aunque llegue mas tarde.
    const peticionActual = useRef(0);

    // Abre el panel al instante y despues pide el detalle a la API.
    function abrir(categoria: CategoriaAlimento) {
        const numero = ++peticionActual.current;
        setSeleccionado(categoria);
        setDetalle(null);
        setEstadoDetalle("cargando");
        getRecursosById(categoria.id_categoria)
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
                setCategorias(data);
                setEstado(data.length === 0 ? "vacio" : "listo");
            })
            .catch(() => {
                setEstado("error");
            });
    }, []);

    return (
        <PageShell
            title="Categorias"
            endpoint="categorias"
            vista="Catalogo simple"
            campos={CAMPOS}
        >
            {estado === "cargando" && (
                <p className="mt-4 text-sm text-gray-500">Cargando...</p>
            )}
            {estado === "error" && (
                <p className="mt-4 text-sm text-red-500">
                    Error al cargar las categorias.
                </p>
            )}
            {estado === "vacio" && (
                <p className="mt-4 text-sm text-gray-500">
                    No hay categorias disponibles.
                </p>
            )}
            {estado === "listo" && (
                <ul className="mt-4 space-y-2">
                    {categorias.map((categoria) => (
                        <li
                            key={categoria.id_categoria}
                            onClick={() => abrir(categoria)}
                            className="flex cursor-pointer items-center gap-4 rounded-lg border border-gray-200 p-4"
                        >
                            <div>
                                <p className="text-sm font-medium text-gray-900">
                                    {categoria.nombre}
                                </p>
                                <p className="text-sm text-gray-500">
                                    ID: {categoria.id_categoria}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Descripcion: {categoria.descripcion}
                                </p>
                                <p className="text-sm text-gray-500">
                                    Estado:{" "}
                                    {categoria.estado ? "Activa" : "Inactiva"}
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
                                ID: {detalle.id_categoria}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Descripcion: {detalle.descripcion}
                            </p>
                            <p className="mt-2 text-sm text-gray-500">
                                Estado:{" "}
                                {detalle.estado ? "Activa" : "Inactiva"}
                            </p>
                        </div>
                    )}
                </PanelLateral>
            )}
        </PageShell>
    );
}
