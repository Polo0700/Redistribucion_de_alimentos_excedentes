<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatosProyectoSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. ROLES
        |--------------------------------------------------------------------------
        */

        DB::table('roles')->insert([
            [
                'id' => 1,
                'nombre' => 'Administrador',
                'descripcion' => 'Administra completamente el sistema.',
                'estado' => true,
            ],
            [
                'id' => 2,
                'nombre' => 'Donador',
                'descripcion' => 'Usuario que registra donaciones.',
                'estado' => true,
            ],
            [
                'id' => 3,
                'nombre' => 'Beneficiario',
                'descripcion' => 'Usuario que solicita alimentos.',
                'estado' => true,
            ],
            [
                'id' => 4,
                'nombre' => 'Coordinador',
                'descripcion' => 'Coordina las actividades del sistema.',
                'estado' => true,
            ],
            [
                'id' => 5,
                'nombre' => 'Voluntario',
                'descripcion' => 'Apoya en actividades de distribución.',
                'estado' => true,
            ],
            [
                'id' => 6,
                'nombre' => 'Repartidor',
                'descripcion' => 'Realiza entregas de alimentos.',
                'estado' => true,
            ],
            [
                'id' => 7,
                'nombre' => 'Supervisor',
                'descripcion' => 'Supervisa las operaciones.',
                'estado' => true,
            ],
            [
                'id' => 8,
                'nombre' => 'Consulta',
                'descripcion' => 'Usuario con acceso de consulta.',
                'estado' => true,
            ],
            [
                'id' => 9,
                'nombre' => 'Organización',
                'descripcion' => 'Representante de una organización.',
                'estado' => true,
            ],
            [
                'id' => 10,
                'nombre' => 'Invitado',
                'descripcion' => 'Usuario con acceso limitado.',
                'estado' => true,
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 2. CATEGORÍAS DE ALIMENTOS
        |--------------------------------------------------------------------------
        */

        DB::table('categorias_alimento')->insert([
            [
                'id_categoria' => 1,
                'nombre' => 'Frutas',
                'descripcion' => 'Frutas frescas.',
                'estado' => true,
            ],
            [
                'id_categoria' => 2,
                'nombre' => 'Verduras',
                'descripcion' => 'Verduras y vegetales.',
                'estado' => true,
            ],
            [
                'id_categoria' => 3,
                'nombre' => 'Granos',
                'descripcion' => 'Arroz, maíz y otros granos.',
                'estado' => true,
            ],
            [
                'id_categoria' => 4,
                'nombre' => 'Legumbres',
                'descripcion' => 'Frijoles, lentejas y similares.',
                'estado' => true,
            ],
            [
                'id_categoria' => 5,
                'nombre' => 'Lácteos',
                'descripcion' => 'Leche y productos lácteos.',
                'estado' => true,
            ],
            [
                'id_categoria' => 6,
                'nombre' => 'Panadería',
                'descripcion' => 'Pan y productos de panadería.',
                'estado' => true,
            ],
            [
                'id_categoria' => 7,
                'nombre' => 'Cereales',
                'descripcion' => 'Cereales y productos derivados.',
                'estado' => true,
            ],
            [
                'id_categoria' => 8,
                'nombre' => 'Enlatados',
                'descripcion' => 'Alimentos enlatados.',
                'estado' => true,
            ],
            [
                'id_categoria' => 9,
                'nombre' => 'Bebidas',
                'descripcion' => 'Agua y bebidas no alcohólicas.',
                'estado' => true,
            ],
            [
                'id_categoria' => 10,
                'nombre' => 'Otros',
                'descripcion' => 'Otros alimentos disponibles.',
                'estado' => true,
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 3. USUARIOS
        |--------------------------------------------------------------------------
        */

        DB::table('usuarios')->insert([
            [
                'id' => 1,
                'id_rol' => 1,
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'correo' => 'juan@example.com',
                'telefono' => '3312345678',
                'direccion' => 'Av. Juárez 100, Guadalajara',
                'fecha_registro' => '2026-09-01 09:00:00',
                'estado' => true,
            ],
            [
                'id' => 2,
                'id_rol' => 2,
                'nombre' => 'María',
                'apellido' => 'López',
                'correo' => 'maria@example.com',
                'telefono' => '3312345679',
                'direccion' => 'Col. Americana, Guadalajara',
                'fecha_registro' => '2026-09-01 10:00:00',
                'estado' => true,
            ],
            [
                'id' => 3,
                'id_rol' => 3,
                'nombre' => 'Carlos',
                'apellido' => 'Ramírez',
                'correo' => 'carlos@example.com',
                'telefono' => '3312345680',
                'direccion' => 'Col. Centro, Guadalajara',
                'fecha_registro' => '2026-09-02 09:30:00',
                'estado' => true,
            ],
            [
                'id' => 4,
                'id_rol' => 4,
                'nombre' => 'Ana',
                'apellido' => 'García',
                'correo' => 'ana@example.com',
                'telefono' => '3312345681',
                'direccion' => 'Col. Moderna, Guadalajara',
                'fecha_registro' => '2026-09-02 11:00:00',
                'estado' => true,
            ],
            [
                'id' => 5,
                'id_rol' => 5,
                'nombre' => 'Luis',
                'apellido' => 'Hernández',
                'correo' => 'luis@example.com',
                'telefono' => '3312345682',
                'direccion' => 'Col. Lafayette, Guadalajara',
                'fecha_registro' => '2026-09-03 08:30:00',
                'estado' => true,
            ],
            [
                'id' => 6,
                'id_rol' => 6,
                'nombre' => 'Sofía',
                'apellido' => 'Martínez',
                'correo' => 'sofia@example.com',
                'telefono' => '3312345683',
                'direccion' => 'Col. Providencia, Guadalajara',
                'fecha_registro' => '2026-09-03 12:00:00',
                'estado' => true,
            ],
            [
                'id' => 7,
                'id_rol' => 7,
                'nombre' => 'Diego',
                'apellido' => 'Torres',
                'correo' => 'diego@example.com',
                'telefono' => '3312345684',
                'direccion' => 'Col. Chapultepec, Guadalajara',
                'fecha_registro' => '2026-09-04 09:15:00',
                'estado' => true,
            ],
            [
                'id' => 8,
                'id_rol' => 8,
                'nombre' => 'Laura',
                'apellido' => 'Sánchez',
                'correo' => 'laura@example.com',
                'telefono' => '3312345685',
                'direccion' => 'Col. Arcos, Guadalajara',
                'fecha_registro' => '2026-09-04 13:00:00',
                'estado' => true,
            ],
            [
                'id' => 9,
                'id_rol' => 9,
                'nombre' => 'Pedro',
                'apellido' => 'Morales',
                'correo' => 'pedro@example.com',
                'telefono' => '3312345686',
                'direccion' => 'Col. Jardines del Bosque, Guadalajara',
                'fecha_registro' => '2026-09-05 10:30:00',
                'estado' => true,
            ],
            [
                'id' => 10,
                'id_rol' => 10,
                'nombre' => 'Elena',
                'apellido' => 'Vargas',
                'correo' => 'elena@example.com',
                'telefono' => '3312345687',
                'direccion' => 'Col. San Juan de Dios, Guadalajara',
                'fecha_registro' => '2026-09-05 15:00:00',
                'estado' => true,
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 4. CUENTAS DE ACCESO
        |--------------------------------------------------------------------------
        */

        DB::table('cuentas_acceso')->insert([
            [
                'id_cuenta' => 1,
                'id_usuario' => 1,
                'proveedor' => 'Correo',
                'identificador_externo' => 'juan@example.com',
                'contrasena_hash' => bcrypt('12345678'),
                'fecha_ultimo_acceso' => '2026-09-09 09:00:00',
            ],
            [
                'id_cuenta' => 2,
                'id_usuario' => 2,
                'proveedor' => 'Correo',
                'identificador_externo' => 'maria@example.com',
                'contrasena_hash' => bcrypt('12345678'),
                'fecha_ultimo_acceso' => '2026-09-09 10:00:00',
            ],
            [
                'id_cuenta' => 3,
                'id_usuario' => 3,
                'proveedor' => 'Google',
                'identificador_externo' => 'carlos@example.com',
                'contrasena_hash' => bcrypt('12345678'),
                'fecha_ultimo_acceso' => '2026-09-08 11:00:00',
            ],
            [
                'id_cuenta' => 4,
                'id_usuario' => 4,
                'proveedor' => 'Correo',
                'identificador_externo' => 'ana@example.com',
                'contrasena_hash' => bcrypt('12345678'),
                'fecha_ultimo_acceso' => '2026-09-08 12:00:00',
            ],
            [
                'id_cuenta' => 5,
                'id_usuario' => 5,
                'proveedor' => 'Google',
                'identificador_externo' => 'luis@example.com',
                'contrasena_hash' => bcrypt('12345678'),
                'fecha_ultimo_acceso' => '2026-09-07 13:00:00',
            ],
            [
                'id_cuenta' => 6,
                'id_usuario' => 6,
                'proveedor' => 'Correo',
                'identificador_externo' => 'sofia@example.com',
                'contrasena_hash' => bcrypt('12345678'),
                'fecha_ultimo_acceso' => '2026-09-07 14:00:00',
            ],
            [
                'id_cuenta' => 7,
                'id_usuario' => 7,
                'proveedor' => 'Google',
                'identificador_externo' => 'diego@example.com',
                'contrasena_hash' => bcrypt('12345678'),
                'fecha_ultimo_acceso' => '2026-09-06 15:00:00',
            ],
            [
                'id_cuenta' => 8,
                'id_usuario' => 8,
                'proveedor' => 'Correo',
                'identificador_externo' => 'laura@example.com',
                'contrasena_hash' => bcrypt('12345678'),
                'fecha_ultimo_acceso' => '2026-09-06 16:00:00',
            ],
            [
                'id_cuenta' => 9,
                'id_usuario' => 9,
                'proveedor' => 'Google',
                'identificador_externo' => 'pedro@example.com',
                'contrasena_hash' => bcrypt('12345678'),
                'fecha_ultimo_acceso' => '2026-09-05 17:00:00',
            ],
            [
                'id_cuenta' => 10,
                'id_usuario' => 10,
                'proveedor' => 'Correo',
                'identificador_externo' => 'elena@example.com',
                'contrasena_hash' => bcrypt('12345678'),
                'fecha_ultimo_acceso' => '2026-09-05 18:00:00',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 5. ALIMENTOS
        |--------------------------------------------------------------------------
        */

        DB::table('alimentos')->insert([
            [
                'id_alimento' => 1,
                'id_categoria' => 1,
                'nombre' => 'Manzana',
                'descripcion' => 'Manzana fresca.',
                'estado' => true,
            ],
            [
                'id_alimento' => 2,
                'id_categoria' => 2,
                'nombre' => 'Zanahoria',
                'descripcion' => 'Zanahoria fresca.',
                'estado' => true,
            ],
            [
                'id_alimento' => 3,
                'id_categoria' => 3,
                'nombre' => 'Arroz',
                'descripcion' => 'Arroz blanco.',
                'estado' => true,
            ],
            [
                'id_alimento' => 4,
                'id_categoria' => 4,
                'nombre' => 'Frijol',
                'descripcion' => 'Frijol negro.',
                'estado' => true,
            ],
            [
                'id_alimento' => 5,
                'id_categoria' => 5,
                'nombre' => 'Leche',
                'descripcion' => 'Leche pasteurizada.',
                'estado' => true,
            ],
            [
                'id_alimento' => 6,
                'id_categoria' => 6,
                'nombre' => 'Pan',
                'descripcion' => 'Pan de caja.',
                'estado' => true,
            ],
            [
                'id_alimento' => 7,
                'id_categoria' => 7,
                'nombre' => 'Avena',
                'descripcion' => 'Avena en hojuelas.',
                'estado' => true,
            ],
            [
                'id_alimento' => 8,
                'id_categoria' => 8,
                'nombre' => 'Atún',
                'descripcion' => 'Atún enlatado.',
                'estado' => true,
            ],
            [
                'id_alimento' => 9,
                'id_categoria' => 9,
                'nombre' => 'Agua',
                'descripcion' => 'Agua embotellada.',
                'estado' => true,
            ],
            [
                'id_alimento' => 10,
                'id_categoria' => 10,
                'nombre' => 'Pasta',
                'descripcion' => 'Pasta alimenticia.',
                'estado' => true,
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 6. DONACIONES
        |--------------------------------------------------------------------------
        */

        DB::table('donaciones')->insert([
            [
                'id_donacion' => 1,
                'id_usuario' => 2,
                'fecha_donacion' => '2026-09-01 09:00:00',
                'fecha_limite' => '2026-09-10',
                'ubicacion' => 'Col. Americana',
                'estado' => 'Disponible',
                'observaciones' => 'Alimentos en buen estado.',
            ],
            [
                'id_donacion' => 2,
                'id_usuario' => 2,
                'fecha_donacion' => '2026-09-01 11:00:00',
                'fecha_limite' => '2026-09-11',
                'ubicacion' => 'Col. Centro',
                'estado' => 'Disponible',
                'observaciones' => 'Productos frescos.',
            ],
            [
                'id_donacion' => 3,
                'id_usuario' => 4,
                'fecha_donacion' => '2026-09-02 10:00:00',
                'fecha_limite' => '2026-09-12',
                'ubicacion' => 'Col. Moderna',
                'estado' => 'Disponible',
                'observaciones' => 'Donación de alimentos.',
            ],
            [
                'id_donacion' => 4,
                'id_usuario' => 5,
                'fecha_donacion' => '2026-09-02 13:00:00',
                'fecha_limite' => '2026-09-12',
                'ubicacion' => 'Col. Lafayette',
                'estado' => 'En proceso',
                'observaciones' => 'Pendiente de distribución.',
            ],
            [
                'id_donacion' => 5,
                'id_usuario' => 6,
                'fecha_donacion' => '2026-09-03 09:30:00',
                'fecha_limite' => '2026-09-13',
                'ubicacion' => 'Col. Providencia',
                'estado' => 'Disponible',
                'observaciones' => 'Productos empaquetados.',
            ],
            [
                'id_donacion' => 6,
                'id_usuario' => 7,
                'fecha_donacion' => '2026-09-03 12:00:00',
                'fecha_limite' => '2026-09-14',
                'ubicacion' => 'Chapultepec',
                'estado' => 'Disponible',
                'observaciones' => 'Alimentos variados.',
            ],
            [
                'id_donacion' => 7,
                'id_usuario' => 8,
                'fecha_donacion' => '2026-09-04 10:00:00',
                'fecha_limite' => '2026-09-14',
                'ubicacion' => 'Col. Arcos',
                'estado' => 'Entregada',
                'observaciones' => 'Donación completada.',
            ],
            [
                'id_donacion' => 8,
                'id_usuario' => 9,
                'fecha_donacion' => '2026-09-04 14:00:00',
                'fecha_limite' => '2026-09-15',
                'ubicacion' => 'Jardines del Bosque',
                'estado' => 'Disponible',
                'observaciones' => 'Productos variados.',
            ],
            [
                'id_donacion' => 9,
                'id_usuario' => 10,
                'fecha_donacion' => '2026-09-05 09:00:00',
                'fecha_limite' => '2026-09-16',
                'ubicacion' => 'San Juan de Dios',
                'estado' => 'Disponible',
                'observaciones' => 'Alimentos empaquetados.',
            ],
            [
                'id_donacion' => 10,
                'id_usuario' => 2,
                'fecha_donacion' => '2026-09-05 16:00:00',
                'fecha_limite' => '2026-09-17',
                'ubicacion' => 'Col. Americana',
                'estado' => 'Disponible',
                'observaciones' => 'Nueva donación.',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 7. DETALLE DE DONACIONES
        |--------------------------------------------------------------------------
        */

        DB::table('detalle_donacion')->insert([
            [
                'id_detalle_donacion' => 1,
                'id_donacion' => 1,
                'id_alimento' => 1,
                'cantidad' => 20,
                'observaciones' => 'Manzanas frescas.',
            ],
            [
                'id_detalle_donacion' => 2,
                'id_donacion' => 2,
                'id_alimento' => 2,
                'cantidad' => 15,
                'observaciones' => 'Zanahorias frescas.',
            ],
            [
                'id_detalle_donacion' => 3,
                'id_donacion' => 3,
                'id_alimento' => 3,
                'cantidad' => 25,
                'observaciones' => 'Arroz empaquetado.',
            ],
            [
                'id_detalle_donacion' => 4,
                'id_donacion' => 4,
                'id_alimento' => 4,
                'cantidad' => 30,
                'observaciones' => 'Frijol empaquetado.',
            ],
            [
                'id_detalle_donacion' => 5,
                'id_donacion' => 5,
                'id_alimento' => 5,
                'cantidad' => 12,
                'observaciones' => 'Leche en buen estado.',
            ],
            [
                'id_detalle_donacion' => 6,
                'id_donacion' => 6,
                'id_alimento' => 6,
                'cantidad' => 20,
                'observaciones' => 'Pan fresco.',
            ],
            [
                'id_detalle_donacion' => 7,
                'id_donacion' => 7,
                'id_alimento' => 7,
                'cantidad' => 10,
                'observaciones' => 'Avena empaquetada.',
            ],
            [
                'id_detalle_donacion' => 8,
                'id_donacion' => 8,
                'id_alimento' => 8,
                'cantidad' => 18,
                'observaciones' => 'Atún enlatado.',
            ],
            [
                'id_detalle_donacion' => 9,
                'id_donacion' => 9,
                'id_alimento' => 9,
                'cantidad' => 40,
                'observaciones' => 'Botellas de agua.',
            ],
            [
                'id_detalle_donacion' => 10,
                'id_donacion' => 10,
                'id_alimento' => 10,
                'cantidad' => 15,
                'observaciones' => 'Pasta empaquetada.',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 8. CARRITOS
        |--------------------------------------------------------------------------
        */

        DB::table('carritos')->insert([
            [
                'id_carrito' => 1,
                'id_usuario' => 3,
                'fecha_creacion' => '2026-09-01 10:00:00',
                'estado' => 'Activo',
            ],
            [
                'id_carrito' => 2,
                'id_usuario' => 3,
                'fecha_creacion' => '2026-09-02 10:00:00',
                'estado' => 'Procesado',
            ],
            [
                'id_carrito' => 3,
                'id_usuario' => 4,
                'fecha_creacion' => '2026-09-02 11:00:00',
                'estado' => 'Activo',
            ],
            [
                'id_carrito' => 4,
                'id_usuario' => 5,
                'fecha_creacion' => '2026-09-03 09:00:00',
                'estado' => 'Activo',
            ],
            [
                'id_carrito' => 5,
                'id_usuario' => 6,
                'fecha_creacion' => '2026-09-03 12:00:00',
                'estado' => 'Finalizado',
            ],
            [
                'id_carrito' => 6,
                'id_usuario' => 7,
                'fecha_creacion' => '2026-09-04 09:00:00',
                'estado' => 'Activo',
            ],
            [
                'id_carrito' => 7,
                'id_usuario' => 8,
                'fecha_creacion' => '2026-09-04 13:00:00',
                'estado' => 'Procesado',
            ],
            [
                'id_carrito' => 8,
                'id_usuario' => 9,
                'fecha_creacion' => '2026-09-05 10:00:00',
                'estado' => 'Activo',
            ],
            [
                'id_carrito' => 9,
                'id_usuario' => 10,
                'fecha_creacion' => '2026-09-05 14:00:00',
                'estado' => 'Activo',
            ],
            [
                'id_carrito' => 10,
                'id_usuario' => 3,
                'fecha_creacion' => '2026-09-06 09:00:00',
                'estado' => 'Finalizado',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 9. DETALLE DE CARRITOS
        |--------------------------------------------------------------------------
        */

        DB::table('carrito_detalle')->insert([
            [
                'id_detalle_carrito' => 1,
                'id_carrito' => 1,
                'id_alimento' => 1,
                'cantidad' => 3,
            ],
            [
                'id_detalle_carrito' => 2,
                'id_carrito' => 2,
                'id_alimento' => 2,
                'cantidad' => 5,
            ],
            [
                'id_detalle_carrito' => 3,
                'id_carrito' => 3,
                'id_alimento' => 3,
                'cantidad' => 2,
            ],
            [
                'id_detalle_carrito' => 4,
                'id_carrito' => 4,
                'id_alimento' => 4,
                'cantidad' => 4,
            ],
            [
                'id_detalle_carrito' => 5,
                'id_carrito' => 5,
                'id_alimento' => 5,
                'cantidad' => 2,
            ],
            [
                'id_detalle_carrito' => 6,
                'id_carrito' => 6,
                'id_alimento' => 6,
                'cantidad' => 3,
            ],
            [
                'id_detalle_carrito' => 7,
                'id_carrito' => 7,
                'id_alimento' => 7,
                'cantidad' => 2,
            ],
            [
                'id_detalle_carrito' => 8,
                'id_carrito' => 8,
                'id_alimento' => 8,
                'cantidad' => 3,
            ],
            [
                'id_detalle_carrito' => 9,
                'id_carrito' => 9,
                'id_alimento' => 9,
                'cantidad' => 5,
            ],
            [
                'id_detalle_carrito' => 10,
                'id_carrito' => 10,
                'id_alimento' => 10,
                'cantidad' => 2,
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 10. LISTAS DE DESEOS
        |--------------------------------------------------------------------------
        */

        DB::table('listas_deseos')->insert([
            [
                'id_lista' => 1,
                'id_usuario' => 3,
                'nombre' => 'Alimentos para la semana',
                'fecha_creacion' => '2026-09-01 10:00:00',
            ],
            [
                'id_lista' => 2,
                'id_usuario' => 4,
                'nombre' => 'Productos básicos',
                'fecha_creacion' => '2026-09-02 10:00:00',
            ],
            [
                'id_lista' => 3,
                'id_usuario' => 5,
                'nombre' => 'Frutas',
                'fecha_creacion' => '2026-09-02 12:00:00',
            ],
            [
                'id_lista' => 4,
                'id_usuario' => 6,
                'nombre' => 'Despensa',
                'fecha_creacion' => '2026-09-03 09:00:00',
            ],
            [
                'id_lista' => 5,
                'id_usuario' => 7,
                'nombre' => 'Alimentos frescos',
                'fecha_creacion' => '2026-09-03 13:00:00',
            ],
            [
                'id_lista' => 6,
                'id_usuario' => 8,
                'nombre' => 'Productos básicos',
                'fecha_creacion' => '2026-09-04 10:00:00',
            ],
            [
                'id_lista' => 7,
                'id_usuario' => 9,
                'nombre' => 'Desayuno',
                'fecha_creacion' => '2026-09-04 14:00:00',
            ],
            [
                'id_lista' => 8,
                'id_usuario' => 10,
                'nombre' => 'Alimentos familiares',
                'fecha_creacion' => '2026-09-05 09:00:00',
            ],
            [
                'id_lista' => 9,
                'id_usuario' => 3,
                'nombre' => 'Granos y legumbres',
                'fecha_creacion' => '2026-09-05 12:00:00',
            ],
            [
                'id_lista' => 10,
                'id_usuario' => 4,
                'nombre' => 'Productos deseados',
                'fecha_creacion' => '2026-09-06 10:00:00',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 11. DETALLE DE LISTAS DE DESEOS
        |--------------------------------------------------------------------------
        */

        DB::table('deseos_detalle')->insert([
            [
                'id_deseo' => 1,
                'id_lista' => 1,
                'id_alimento' => 1,
                'fecha_agregado' => '2026-09-01 10:30:00',
            ],
            [
                'id_deseo' => 2,
                'id_lista' => 2,
                'id_alimento' => 2,
                'fecha_agregado' => '2026-09-02 10:30:00',
            ],
            [
                'id_deseo' => 3,
                'id_lista' => 3,
                'id_alimento' => 3,
                'fecha_agregado' => '2026-09-02 12:30:00',
            ],
            [
                'id_deseo' => 4,
                'id_lista' => 4,
                'id_alimento' => 4,
                'fecha_agregado' => '2026-09-03 09:30:00',
            ],
            [
                'id_deseo' => 5,
                'id_lista' => 5,
                'id_alimento' => 5,
                'fecha_agregado' => '2026-09-03 13:30:00',
            ],
            [
                'id_deseo' => 6,
                'id_lista' => 6,
                'id_alimento' => 6,
                'fecha_agregado' => '2026-09-04 10:30:00',
            ],
            [
                'id_deseo' => 7,
                'id_lista' => 7,
                'id_alimento' => 7,
                'fecha_agregado' => '2026-09-04 14:30:00',
            ],
            [
                'id_deseo' => 8,
                'id_lista' => 8,
                'id_alimento' => 8,
                'fecha_agregado' => '2026-09-05 09:30:00',
            ],
            [
                'id_deseo' => 9,
                'id_lista' => 9,
                'id_alimento' => 9,
                'fecha_agregado' => '2026-09-05 12:30:00',
            ],
            [
                'id_deseo' => 10,
                'id_lista' => 10,
                'id_alimento' => 10,
                'fecha_agregado' => '2026-09-06 10:30:00',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 12. SOLICITUDES
        |--------------------------------------------------------------------------
        */

        DB::table('solicitudes')->insert([
            [
                'id_solicitud' => 1,
                'id_usuario' => 3,
                'fecha_solicitud' => '2026-09-01 11:00:00',
                'estado' => 'Pendiente',
                'direccion_entrega' => 'Col. Centro, Guadalajara',
                'observaciones' => 'Solicitud de alimentos básicos.',
            ],
            [
                'id_solicitud' => 2,
                'id_usuario' => 4,
                'fecha_solicitud' => '2026-09-02 11:00:00',
                'estado' => 'Aprobada',
                'direccion_entrega' => 'Col. Moderna, Guadalajara',
                'observaciones' => 'Solicitud aprobada.',
            ],
            [
                'id_solicitud' => 3,
                'id_usuario' => 5,
                'fecha_solicitud' => '2026-09-02 14:00:00',
                'estado' => 'Pendiente',
                'direccion_entrega' => 'Col. Lafayette, Guadalajara',
                'observaciones' => 'Pendiente de revisión.',
            ],
            [
                'id_solicitud' => 4,
                'id_usuario' => 6,
                'fecha_solicitud' => '2026-09-03 10:00:00',
                'estado' => 'En proceso',
                'direccion_entrega' => 'Col. Providencia, Guadalajara',
                'observaciones' => 'En preparación.',
            ],
            [
                'id_solicitud' => 5,
                'id_usuario' => 7,
                'fecha_solicitud' => '2026-09-03 14:00:00',
                'estado' => 'Aprobada',
                'direccion_entrega' => 'Chapultepec, Guadalajara',
                'observaciones' => 'Solicitud aprobada.',
            ],
            [
                'id_solicitud' => 6,
                'id_usuario' => 8,
                'fecha_solicitud' => '2026-09-04 11:00:00',
                'estado' => 'Pendiente',
                'direccion_entrega' => 'Col. Arcos, Guadalajara',
                'observaciones' => 'Pendiente de asignación.',
            ],
            [
                'id_solicitud' => 7,
                'id_usuario' => 9,
                'fecha_solicitud' => '2026-09-04 15:00:00',
                'estado' => 'Entregada',
                'direccion_entrega' => 'Jardines del Bosque, Guadalajara',
                'observaciones' => 'Solicitud completada.',
            ],
            [
                'id_solicitud' => 8,
                'id_usuario' => 10,
                'fecha_solicitud' => '2026-09-05 10:00:00',
                'estado' => 'Pendiente',
                'direccion_entrega' => 'San Juan de Dios, Guadalajara',
                'observaciones' => 'Pendiente.',
            ],
            [
                'id_solicitud' => 9,
                'id_usuario' => 3,
                'fecha_solicitud' => '2026-09-05 13:00:00',
                'estado' => 'Aprobada',
                'direccion_entrega' => 'Col. Centro, Guadalajara',
                'observaciones' => 'Solicitud aprobada.',
            ],
            [
                'id_solicitud' => 10,
                'id_usuario' => 4,
                'fecha_solicitud' => '2026-09-06 09:00:00',
                'estado' => 'En proceso',
                'direccion_entrega' => 'Col. Moderna, Guadalajara',
                'observaciones' => 'En preparación.',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 13. DETALLE DE SOLICITUDES
        |--------------------------------------------------------------------------
        */

        DB::table('detalle_solicitud')->insert([
            [
                'id_detalle_solicitud' => 1,
                'id_solicitud' => 1,
                'id_alimento' => 1,
                'cantidad' => 5,
                'estado' => 'Pendiente',
            ],
            [
                'id_detalle_solicitud' => 2,
                'id_solicitud' => 2,
                'id_alimento' => 2,
                'cantidad' => 4,
                'estado' => 'Aprobado',
            ],
            [
                'id_detalle_solicitud' => 3,
                'id_solicitud' => 3,
                'id_alimento' => 3,
                'cantidad' => 3,
                'estado' => 'Pendiente',
            ],
            [
                'id_detalle_solicitud' => 4,
                'id_solicitud' => 4,
                'id_alimento' => 4,
                'cantidad' => 5,
                'estado' => 'En proceso',
            ],
            [
                'id_detalle_solicitud' => 5,
                'id_solicitud' => 5,
                'id_alimento' => 5,
                'cantidad' => 2,
                'estado' => 'Aprobado',
            ],
            [
                'id_detalle_solicitud' => 6,
                'id_solicitud' => 6,
                'id_alimento' => 6,
                'cantidad' => 3,
                'estado' => 'Pendiente',
            ],
            [
                'id_detalle_solicitud' => 7,
                'id_solicitud' => 7,
                'id_alimento' => 7,
                'cantidad' => 2,
                'estado' => 'Entregado',
            ],
            [
                'id_detalle_solicitud' => 8,
                'id_solicitud' => 8,
                'id_alimento' => 8,
                'cantidad' => 4,
                'estado' => 'Pendiente',
            ],
            [
                'id_detalle_solicitud' => 9,
                'id_solicitud' => 9,
                'id_alimento' => 9,
                'cantidad' => 5,
                'estado' => 'Aprobado',
            ],
            [
                'id_detalle_solicitud' => 10,
                'id_solicitud' => 10,
                'id_alimento' => 10,
                'cantidad' => 3,
                'estado' => 'En proceso',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 14. ENTREGAS
        |--------------------------------------------------------------------------
        */

        DB::table('entregas')->insert([
            [
                'id_entrega' => 1,
                'id_solicitud' => 1,
                'fecha_entrega' => '2026-09-07 09:00:00',
                'responsable' => 'Carlos Ramírez',
                'estado' => 'Entregada',
                'observaciones' => 'Entrega realizada correctamente.',
            ],
            [
                'id_entrega' => 2,
                'id_solicitud' => 2,
                'fecha_entrega' => '2026-09-07 10:00:00',
                'responsable' => 'Luis Hernández',
                'estado' => 'Entregada',
                'observaciones' => 'Entrega completada.',
            ],
            [
                'id_entrega' => 3,
                'id_solicitud' => 3,
                'fecha_entrega' => '2026-09-08 09:00:00',
                'responsable' => 'Sofía Martínez',
                'estado' => 'Pendiente',
                'observaciones' => 'Pendiente de entrega.',
            ],
            [
                'id_entrega' => 4,
                'id_solicitud' => 4,
                'fecha_entrega' => '2026-09-08 11:00:00',
                'responsable' => 'Diego Torres',
                'estado' => 'En camino',
                'observaciones' => 'Entrega en camino.',
            ],
            [
                'id_entrega' => 5,
                'id_solicitud' => 5,
                'fecha_entrega' => '2026-09-08 13:00:00',
                'responsable' => 'Carlos Ramírez',
                'estado' => 'Entregada',
                'observaciones' => 'Entrega completada.',
            ],
            [
                'id_entrega' => 6,
                'id_solicitud' => 6,
                'fecha_entrega' => '2026-09-09 09:00:00',
                'responsable' => 'Luis Hernández',
                'estado' => 'Pendiente',
                'observaciones' => 'Esperando asignación.',
            ],
            [
                'id_entrega' => 7,
                'id_solicitud' => 7,
                'fecha_entrega' => '2026-09-09 10:00:00',
                'responsable' => 'Sofía Martínez',
                'estado' => 'Entregada',
                'observaciones' => 'Entrega realizada.',
            ],
            [
                'id_entrega' => 8,
                'id_solicitud' => 8,
                'fecha_entrega' => '2026-09-10 09:00:00',
                'responsable' => 'Diego Torres',
                'estado' => 'Pendiente',
                'observaciones' => 'Pendiente de entrega.',
            ],
            [
                'id_entrega' => 9,
                'id_solicitud' => 9,
                'fecha_entrega' => '2026-09-10 11:00:00',
                'responsable' => 'Carlos Ramírez',
                'estado' => 'En camino',
                'observaciones' => 'Entrega en proceso.',
            ],
            [
                'id_entrega' => 10,
                'id_solicitud' => 10,
                'fecha_entrega' => '2026-09-10 13:00:00',
                'responsable' => 'Luis Hernández',
                'estado' => 'Pendiente',
                'observaciones' => 'Pendiente de entrega.',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 15. ACCIONES IMPORTANTES
        |--------------------------------------------------------------------------
        */

        DB::table('acciones_importantes')->insert([
            [
                'id_accion' => 1,
                'id_usuario' => 1,
                'accion' => 'Registro',
                'tabla_afectada' => 'usuarios',
                'descripcion' => 'Se registró un usuario.',
                'fecha_hora' => '2026-09-01 09:00:00',
                'ip_origen' => '127.0.0.1',
            ],
            [
                'id_accion' => 2,
                'id_usuario' => 2,
                'accion' => 'Registro',
                'tabla_afectada' => 'donaciones',
                'descripcion' => 'Se registró una donación.',
                'fecha_hora' => '2026-09-01 10:00:00',
                'ip_origen' => '127.0.0.1',
            ],
            [
                'id_accion' => 3,
                'id_usuario' => 3,
                'accion' => 'Registro',
                'tabla_afectada' => 'solicitudes',
                'descripcion' => 'Se registró una solicitud.',
                'fecha_hora' => '2026-09-02 10:00:00',
                'ip_origen' => '127.0.0.1',
            ],
            [
                'id_accion' => 4,
                'id_usuario' => 4,
                'accion' => 'Actualización',
                'tabla_afectada' => 'alimentos',
                'descripcion' => 'Se actualizó un alimento.',
                'fecha_hora' => '2026-09-02 12:00:00',
                'ip_origen' => '127.0.0.1',
            ],
            [
                'id_accion' => 5,
                'id_usuario' => 5,
                'accion' => 'Registro',
                'tabla_afectada' => 'carritos',
                'descripcion' => 'Se creó un carrito.',
                'fecha_hora' => '2026-09-03 09:00:00',
                'ip_origen' => '127.0.0.1',
            ],
            [
                'id_accion' => 6,
                'id_usuario' => 6,
                'accion' => 'Registro',
                'tabla_afectada' => 'listas_deseos',
                'descripcion' => 'Se creó una lista de deseos.',
                'fecha_hora' => '2026-09-03 12:00:00',
                'ip_origen' => '127.0.0.1',
            ],
            [
                'id_accion' => 7,
                'id_usuario' => 7,
                'accion' => 'Actualización',
                'tabla_afectada' => 'solicitudes',
                'descripcion' => 'Se actualizó una solicitud.',
                'fecha_hora' => '2026-09-04 10:00:00',
                'ip_origen' => '127.0.0.1',
            ],
            [
                'id_accion' => 8,
                'id_usuario' => 8,
                'accion' => 'Registro',
                'tabla_afectada' => 'entregas',
                'descripcion' => 'Se registró una entrega.',
                'fecha_hora' => '2026-09-04 14:00:00',
                'ip_origen' => '127.0.0.1',
            ],
            [
                'id_accion' => 9,
                'id_usuario' => 9,
                'accion' => 'Actualización',
                'tabla_afectada' => 'donaciones',
                'descripcion' => 'Se actualizó una donación.',
                'fecha_hora' => '2026-09-05 11:00:00',
                'ip_origen' => '127.0.0.1',
            ],
            [
                'id_accion' => 10,
                'id_usuario' => 10,
                'accion' => 'Registro',
                'tabla_afectada' => 'acciones_importantes',
                'descripcion' => 'Se registró una acción importante.',
                'fecha_hora' => '2026-09-05 15:00:00',
                'ip_origen' => '127.0.0.1',
            ],
        ]);
    }
}