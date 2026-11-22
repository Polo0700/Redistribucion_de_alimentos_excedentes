import type { Entrega } from "../types/entrega";
function getRecursos(): Promise<Entrega[]> {
    return fetch("http://localhost:8000/api/v1/admin/entregas", {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as Entrega[]);
}
function getRecursosById(id: number): Promise<Entrega | null> {
    return fetch(`http://localhost:8000/api/v1/admin/entregas/${id}`, {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as Entrega | null);
}
export { getRecursos, getRecursosById };
