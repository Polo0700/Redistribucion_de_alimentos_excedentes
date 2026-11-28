import { apiUrl } from "../config";
import type { AccionImportante } from "../types/accion";
import { ApiError } from "./errores";
import { sendrequest } from "./http";
function getRecursos(): Promise<AccionImportante[]> {
    return sendrequest<AccionImportante>(apiUrl("acciones-importantes"));
}
function getRecursosById(id: number): Promise<AccionImportante | null> {
    return fetch(apiUrl(`acciones-importantes/${id}`), {
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
        .then((cuerpo) => cuerpo.data as AccionImportante | null)
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
