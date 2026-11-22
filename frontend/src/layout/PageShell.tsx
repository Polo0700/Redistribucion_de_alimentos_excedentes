import type { ReactNode } from "react";
import type { Campo } from "../types/campo";

interface PageShellProps {
    // Titulo del recurso.
    title: string;
    // Segmento del recurso en la API. Ej: alimentos
    endpoint: string;
    // Categoria de la vista: catalogo, directorio, transaccion, log...
    vista: string;
    // Campos propios de ESTA pagina. No se comparten con las demas.
    campos: Campo[];
    // true si el recurso tiene imagen.
    imagenes?: boolean;
    // Relaciones anidadas que trae el backend.
    relaciones?: string[];
    // Contenido extra propio de la pagina.
    children?: ReactNode;
}

// Plantilla COMPARTIDA por todas las paginas.
//
// Aporta unicamente la cabecera y la parte de abajo, para que el layout
// sea consistente. El cuerpo (los campos) lo aporta cada pagina, asi que
// ninguna se ve igual a otra.
export function PageShell({
    title,
    endpoint,
    vista,
    campos,
    imagenes = false,
    relaciones = [],
    children,
}: PageShellProps) {
    return (
        <div className="mx-auto max-w-4xl">
            {/* ---------------- CABECERA (compartida) ---------------- */}
            <div className="rounded-xl border border-gray-200 bg-white p-6">
                <div className="flex flex-wrap items-start justify-between gap-3">
                    <div className="min-w-0">
                        <p className="text-xs font-medium uppercase tracking-wider text-gray-400">
                            {vista}
                        </p>
                        <h2 className="mt-1 truncate text-xl font-semibold text-gray-900">
                            {title}
                        </h2>
                    </div>

                    <code className="rounded-md bg-gray-100 px-2.5 py-1 text-xs text-gray-600">
                        GET /api/v1/admin/{endpoint}
                    </code>
                </div>

                <div className="mt-4 flex flex-wrap gap-2">
                    <span
                        className={[
                            "rounded-full px-2.5 py-0.5 text-xs",
                            imagenes
                                ? "bg-emerald-50 text-emerald-700"
                                : "bg-gray-100 text-gray-500",
                        ].join(" ")}
                    >
                        {imagenes ? "Con imagen" : "Sin imagen"}
                    </span>

                    {relaciones.map((rel) => (
                        <span
                            key={rel}
                            className="rounded-full bg-blue-50 px-2.5 py-0.5 text-xs text-blue-700"
                        >
                            Relacion: {rel}
                        </span>
                    ))}
                </div>
            </div>

            {/* ------------- CUERPO: campos de ESTA pagina ------------- */}
            <div className="mt-4 overflow-hidden rounded-xl border border-gray-200 bg-white">
                <div className="border-b border-gray-100 px-6 py-3">
                    <p className="text-sm font-medium text-gray-700">
                        Campos de este recurso
                    </p>
                    <p className="mt-0.5 text-xs text-gray-400">
                        {campos.length} campos devueltos por el JsonResource del
                        backend
                    </p>
                </div>

                <ul className="divide-y divide-gray-50 px-6">
                    {campos.map((campo) => (
                        <li
                            key={campo.name}
                            className="flex flex-wrap items-baseline gap-x-4 gap-y-1 py-2.5"
                        >
                            <code className="w-52 shrink-0 font-mono text-sm font-medium text-gray-800">
                                {campo.name}
                            </code>
                            <span className="w-20 shrink-0 text-xs uppercase tracking-wide text-gray-400">
                                {campo.type}
                            </span>
                            <span className="min-w-0 flex-1 text-sm text-gray-500">
                                {campo.hint}
                            </span>
                        </li>
                    ))}
                </ul>
            </div>
            <div className="mt-4 rounded-xl border border-gray-200 bg-white p-6">
                <p className="text-sm font-medium text-gray-700">
                    Registros
                </p>

                {children}

                <p className="mt-6 border-t border-gray-100 pt-4 text-xs text-gray-400">
                    Logica HTTP en{" "}
                    <code className="font-mono">src/services/</code> · tipos en{" "}
                    <code className="font-mono">src/types/</code>
                </p>
            </div>
        </div>
    );
}
