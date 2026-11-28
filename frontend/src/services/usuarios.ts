import { apiUrl } from "../config";
import type { Usuario } from "../types/usuario";
import { ApiError } from "./errores";
import { sendrequest } from "./http";
function getRecursos(): Promise<Usuario[]> {
    return sendrequest<Usuario>(apiUrl("usuarios"));
}
function getRecursosById(id: number): Promise<Usuario | null> {
    return fetch(apiUrl(`usuarios/${id}`), {
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
        .then((cuerpo) => cuerpo.data as Usuario | null)
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
