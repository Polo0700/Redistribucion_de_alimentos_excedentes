import type { Administrador } from "../types/administrador";
function getRecursos(): Promise<Administrador[]> {
    return fetch("http://localhost:8000/api/v1/admin/administradores", {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as Administrador[]);
}
function getRecursosById(id: number): Promise<Administrador | null> {
    return fetch(`http://localhost:8000/api/v1/admin/administradores/${id}`, {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as Administrador | null);
}
export { getRecursos, getRecursosById };
