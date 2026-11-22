import type { Donacion } from "../types/donacion";
function getRecursos(): Promise<Donacion[]> {
    return fetch("http://localhost:8000/api/v1/admin/donaciones", {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as Donacion[]);
}
function getRecursosById(id: number): Promise<Donacion | null> {
    return fetch(`http://localhost:8000/api/v1/admin/donaciones/${id}`, {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as Donacion | null);
}
export { getRecursos, getRecursosById };
