type Props = { texto?: string };
export function Spinner({ texto = "Cargando..." }: Props) {
    return (
        <div className="mt-4 flex items-center gap-2 text-sm text-gray-500">
            <div className="h-4 w-4 animate-spin rounded-full border-2 border-gray-300 border-t-gray-600"></div>
            <span>{texto}</span>
        </div>
    );
}
