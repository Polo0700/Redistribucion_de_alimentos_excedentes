import type { CuentaAcceso } from "../types/cuentaAcceso";
function getRecursos(): Promise<CuentaAcceso[]> {
    return fetch("http://localhost:8000/api/v1/admin/cuentas-acceso", {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as CuentaAcceso[]);
}
function getRecursosById(id: number): Promise<CuentaAcceso | null> {
    return fetch(`http://localhost:8000/api/v1/admin/cuentas-acceso/${id}`, {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as CuentaAcceso | null);
}
export { getRecursos, getRecursosById };
