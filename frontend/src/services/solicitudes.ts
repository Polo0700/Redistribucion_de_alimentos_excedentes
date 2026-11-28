import { apiUrl } from "../config";
import type { Solicitud } from "../types/solicitud";
import { ApiError } from "./errores";
import { sendrequest } from "./http";
function getRecursos(): Promise<Solicitud[]> {
    return sendrequest<Solicitud>(
        apiUrl("solicitudes"),
    );
}
function getRecursosById(id: number): Promise<Solicitud | null> {
    return fetch(apiUrl(`solicitudes/${id}`), {
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
        .then((cuerpo) => cuerpo.data as Solicitud | null)
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
