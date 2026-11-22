// Tipos de dato que puede tener un campo devuelto por la API.
export type TipoCampo =
  | 'pk'
  | 'fk'
  | 'texto'
  | 'numero'
  | 'booleano'
  | 'fecha'
  | 'datetime'
  | 'imagen'
  | 'relacion'

// Descriptor de un campo del recurso, tal cual llega desde el
// JsonResource del backend (app/Http/Resources/).
export interface Campo {
    // Nombre tal cual viene en el JSON.
    name: string;
    // Tipo, para saber como renderizarlo.
    type: TipoCampo;
    // Descripcion corta de que muestra.
    hint?: string;
}
