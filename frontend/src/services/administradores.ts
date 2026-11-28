import { apiUrl } from "../config";
import type { Administrador } from "../types/administrador";
import { ApiError } from "./errores";
import { sendrequest } from "./http";
function getRecursos(): Promise<Administrador[]> {
    return sendrequest<Administrador>(
        apiUrl("administradores"),
    );
}
function getRecursosById(id: number): Promise<Administrador | null> {
    return fetch(apiUrl(`administradores/${id}`), {
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
        .then((cuerpo) => cuerpo.data as Administrador | null)
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
