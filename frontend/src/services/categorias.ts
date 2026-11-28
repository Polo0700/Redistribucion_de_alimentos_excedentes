import { apiUrl } from "../config";
import type { CategoriaAlimento } from "../types/categoria";
import { ApiError } from "./errores";
import { sendrequest } from "./http";
function getRecursos(): Promise<CategoriaAlimento[]> {
    return sendrequest<CategoriaAlimento>(
        apiUrl("categorias"),
    );
}
function getRecursosById(id: number): Promise<CategoriaAlimento | null> {
    return fetch(apiUrl(`categorias/${id}`), {
        headers: { Accept: "application/json" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new ApiError(
                    response.status,
                    `HTTP error! status: ${response.status}`,
                );
            }
            return response.json();
        })
        .then((cuerpo) => cuerpo.data as CategoriaAlimento | null)
        .catch((error) => {
            if (error instanceof ApiError) {
                throw error;
            }
            throw new ApiError(
                null,
                `Error desconocido al conectarse con la API: ${error}`,
            );
        });
}
export { getRecursos, getRecursosById };
