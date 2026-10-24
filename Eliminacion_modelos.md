## se elimino modelos 2
CarritoDetalle.php"
Carrito.php"

## eliminacion de un controlador
carrito.controller.php

## eliminacion de vistas 
resources\views\carritos"
use App\Http\Controllers\CarritoController;
estructura carritos aproximado linea 209 y 229

### `app/Models/Usuario.php` eliminar metodo carrito
metodo borrado : public function carritos(): HasMany
    {
        return $this->hasMany(Carrito::class, 'id_usuario', 'id');
    }

### app/Models/Alimento.php se elimino = detallesCarrito() 

## eliminacion de seenders carrito



