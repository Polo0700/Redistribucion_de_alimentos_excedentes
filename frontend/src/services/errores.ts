export class ApiError extends Error {
    status: number | null;
    constructor(status: number | null, message: string) {
        super(message);
        this.status = status;
        this.name = "ApiError";
    }
}
const MENSAJES_ERROR: Record<number, string> = {
    400: "Solicitud incorrecta. Por favor, revise los datos enviados.",
    401: "No autorizado. Por favor, inicie sesión.",
    403: "Prohibido. No tiene permisos para acceder a este recurso.",
    404: "Recurso no encontrado. Por favor, verifique la URL.",
    500: "Error interno del servidor. Por favor, inténtelo más tarde.",
    502: "Puerta de enlace incorrecta. Por favor, inténtelo más tarde.",
    503: "Servicio no disponible. Por favor, inténtelo más tarde.",
    504: "Tiempo de espera de la puerta de enlace agotado. Por favor, inténtelo más tarde.",
};
export function errorMessage(error: unknown): string {
    if (!(error instanceof ApiError)) {
        return "Error desconocido. Por favor, inténtelo más tarde.";
    }
    if (error.status === null) {
        return "No se pudo conectar con la API. Verifique que se encuentre conectado el servidor.";
    }
    return (
        MENSAJES_ERROR[error.status] ??
        `Parece que algo no funcionó. Código de error: ${error.status}.`
    );
}
