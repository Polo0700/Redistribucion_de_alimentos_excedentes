import { apiUrl } from "../config";
import type { CuentaAcceso } from "../types/cuentaAcceso";
import { ApiError } from "./errores";
import { sendrequest } from "./http";
function getRecursos(): Promise<CuentaAcceso[]> {
    return sendrequest<CuentaAcceso>(
        apiUrl("cuentas-acceso"),
    );
}
function getRecursosById(id: number): Promise<CuentaAcceso | null> {
    return fetch(apiUrl(`cuentas-acceso/${id}`), {
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
        .then((cuerpo) => cuerpo.data as CuentaAcceso | null)
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
