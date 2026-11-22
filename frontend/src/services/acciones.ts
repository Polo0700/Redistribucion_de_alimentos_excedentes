import type { AccionImportante } from "../types/accion";
function getRecursos(): Promise<AccionImportante[]> {
    return fetch("http://localhost:8000/api/v1/admin/acciones-importantes", {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as AccionImportante[]);
}
function getRecursosById(id: number): Promise<AccionImportante | null> {
    return fetch(`http://localhost:8000/api/v1/admin/acciones-importantes/${id}`, {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as AccionImportante | null);
}
export { getRecursos, getRecursosById };
