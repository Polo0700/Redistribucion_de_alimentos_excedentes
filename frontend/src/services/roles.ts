import { apiUrl } from "../config";
import type { Rol } from "../types/rol";
import { ApiError } from "./errores";
import { sendrequest } from "./http";
function getRecursos(): Promise<Rol[]> {
    return sendrequest<Rol>(apiUrl("roles"));
}
function getRecursosById(id: number): Promise<Rol | null> {
    return fetch(apiUrl(`roles/${id}`), {
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
        .then((cuerpo) => cuerpo.data as Rol | null)
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
