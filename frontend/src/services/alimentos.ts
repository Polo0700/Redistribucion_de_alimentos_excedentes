import type { Alimento } from "../types/alimento";
function getRecursos(): Promise<Alimento[]> {
    return fetch("http://localhost:8000/api/v1/admin/alimentos", {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as Alimento[]);
}
function getRecursosById(id: number): Promise<Alimento | null> {
    return fetch(`http://localhost:8000/api/v1/admin/alimentos/${id}`, {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as Alimento | null);
}
export { getRecursos, getRecursosById };
