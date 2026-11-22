import type { Solicitud } from "../types/solicitud";
function getRecursos(): Promise<Solicitud[]> {
    return fetch("http://localhost:8000/api/v1/admin/solicitudes", {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as Solicitud[]);
}
function getRecursosById(id: number): Promise<Solicitud | null> {
    return fetch(`http://localhost:8000/api/v1/admin/solicitudes/${id}`, {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as Solicitud | null);
}
export { getRecursos, getRecursosById };
