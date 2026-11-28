import { apiUrl } from "../config";
import type { Alimento } from "../types/alimento";
import { ApiError } from "./errores";
import { sendrequest } from "./http";
function getRecursos(): Promise<Alimento[]> {
    return sendrequest<Alimento>(apiUrl("alimentos"));
}
function getRecursosById(id: number): Promise<Alimento | null> {
    return fetch(apiUrl(`alimentos/${id}`), {
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
        .then((cuerpo) => cuerpo.data as Alimento | null)
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
