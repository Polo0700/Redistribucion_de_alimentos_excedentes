import type { CategoriaAlimento } from "../types/categoria";
function getRecursos(): Promise<CategoriaAlimento[]> {
    return fetch("http://localhost:8000/api/v1/admin/categorias", {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as CategoriaAlimento[]);
}
function getRecursosById(id: number): Promise<CategoriaAlimento | null> {
    return fetch(`http://localhost:8000/api/v1/admin/categorias/${id}`, {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as CategoriaAlimento | null);
}
export { getRecursos, getRecursosById };
