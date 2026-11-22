import type { Rol } from "../types/rol";
function getRecursos(): Promise<Rol[]> {
    return fetch("http://localhost:8000/api/v1/admin/roles", {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as Rol[]);
}
function getRecursosById(id: number): Promise<Rol | null> {
    return fetch(`http://localhost:8000/api/v1/admin/roles/${id}`, {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as Rol | null);
}
export { getRecursos, getRecursosById };
