import type { Usuario } from "../types/usuario";
function getRecursos(): Promise<Usuario[]> {
    return fetch("http://localhost:8000/api/v1/admin/usuarios", {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as Usuario[]);
}
function getRecursosById(id: number): Promise<Usuario | null> {
    return fetch(`http://localhost:8000/api/v1/admin/usuarios/${id}`, {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as Usuario | null);
}
export { getRecursos, getRecursosById };
