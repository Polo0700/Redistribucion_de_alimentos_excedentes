import type { ReactNode } from "react";

// Contrato del panel: el padre decide titulo, contenido y que hacer al cerrar.
interface PanelLateralProps {
    titulo: string;
    children: ReactNode;
    onCerrar: () => void;
}

// Plantilla del detalle. No guarda estado: solo pinta y avisa del cierre.
export function PanelLateral({ titulo, children, onCerrar }: PanelLateralProps) {
    return (
        <aside className="fixed inset-y-0 right-0 z-50 w-96 overflow-y-auto bg-white p-6 shadow-xl">
            <div className="flex items-center justify-between">
                <h3 className="text-lg font-semibold">{titulo}</h3>
                <button
                    onClick={onCerrar}
                    className="rounded-md px-2 py-1 text-sm text-gray-500 hover:bg-gray-100"
                >
                    Cerrar
                </button>
            </div>
            <div className="mt-4">{children}</div>
        </aside>
    );
}
