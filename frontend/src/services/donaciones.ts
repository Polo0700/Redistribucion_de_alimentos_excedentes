import { apiUrl } from "../config";
import type { Donacion } from "../types/donacion";
import { ApiError } from "./errores";
import { sendrequest } from "./http";
function getRecursos(): Promise<Donacion[]> {
    return sendrequest<Donacion>(
        apiUrl("donaciones"),
    );
}
function getRecursosById(id: number): Promise<Donacion | null> {
    return fetch(apiUrl(`donaciones/${id}`), {
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
        .then((cuerpo) => cuerpo.data as Donacion | null)
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
