import { ApiError } from "./errores";
type peticion<T> = {
    data: T[];
    links: { next: string | null };
};
export async function sendrequest<T>(url: string): Promise<T[]> {
    const conjunto: T[] = [];
    let urls: string | null = url;
    while (urls) {
        try {
            const respuesta = await fetch(urls, {
                headers: { accept: "application/json" },
            });
            if (!respuesta.ok) {
                throw new ApiError(
                    respuesta.status,
                    `ERROR HTTP status: ${respuesta.status}`,
                );
            }
            const res: peticion<T> = await respuesta.json();
            conjunto.push(...res.data);
            urls = res.links?.next ?? null;
        } catch (error) {
            if (error instanceof ApiError) {
                throw error;
            }
            throw new ApiError(
                null,
                `Error desconocido al conectarse con la API: ${error}`,
            );
        }
    }
    return conjunto;
}
