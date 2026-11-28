import { apiUrl } from "../config";
import type { Entrega } from "../types/entrega";
import { ApiError } from "./errores";
import { sendrequest } from "./http";
function getRecursos(): Promise<Entrega[]> {
    return sendrequest<Entrega>(apiUrl("entregas"));
}
function getRecursosById(id: number): Promise<Entrega | null> {
    return fetch(apiUrl(`entregas/${id}`), {
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
        .then((cuerpo) => cuerpo.data as Entrega | null)
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
