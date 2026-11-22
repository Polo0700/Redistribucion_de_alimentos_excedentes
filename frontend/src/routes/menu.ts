import type { ComponentType } from 'react'
import type { LucideIcon } from 'lucide-react'
import {
  Apple,
  ClipboardList,
  Folder,
  Gift,
  KeyRound,
  Shield,
  Star,
  Truck,
  UserCog,
  Users,
} from 'lucide-react'

import AccionesPage from '../pages/AccionesPage'
import AdministradoresPage from '../pages/AdministradoresPage'
import AlimentosPage from '../pages/AlimentosPage'
import CategoriasPage from '../pages/CategoriasPage'
import CuentasAccesoPage from '../pages/CuentasAccesoPage'
import DonacionesPage from '../pages/DonacionesPage'
import EntregasPage from '../pages/EntregasPage'
import RolesPage from '../pages/RolesPage'
import SolicitudesPage from '../pages/SolicitudesPage'
import UsuariosPage from '../pages/UsuariosPage'

// FUENTE UNICA DE VERDAD del menu administrativo.
//
// De este array se generan a la vez:
//   - los items del menu lateral (Sidebar)
//   - las rutas de la aplicacion (App)
//
// Para agregar un recurso basta con agregar UNA entrada aqui.
//
// Regla del proyecto: NUNCA usar emojis. Los iconos son SVG de lucide-react.
export interface MenuItem {
    // Ruta en el navegador. Ej: /alimentos
    path: string;
    // Texto visible en el menu lateral.
    label: string;
    // Segmento del recurso en la API. Ej: alimentos -> GET /api/v1/admin/alimentos
    endpoint: string;
    // Icono SVG. Usar componentes de lucide-react, jamas emojis.
    icon: LucideIcon;
    // Componente de la pagina asociada a esta ruta.
    Component: ComponentType;
}

export const MENU: MenuItem[] = [
  {
    path: '/alimentos',
    label: 'Alimentos',
    endpoint: 'alimentos',
    icon: Apple,
    Component: AlimentosPage,
  },
  {
    path: '/categorias',
    label: 'Categorias',
    endpoint: 'categorias',
    icon: Folder,
    Component: CategoriasPage,
  },
  {
    path: '/usuarios',
    label: 'Usuarios',
    endpoint: 'usuarios',
    icon: Users,
    Component: UsuariosPage,
  },
  {
    path: '/cuentas-acceso',
    label: 'Cuentas de acceso',
    endpoint: 'cuentas-acceso',
    icon: KeyRound,
    Component: CuentasAccesoPage,
  },
  {
    path: '/roles',
    label: 'Roles',
    endpoint: 'roles',
    icon: Shield,
    Component: RolesPage,
  },
  {
    path: '/administradores',
    label: 'Administradores',
    endpoint: 'administradores',
    icon: UserCog,
    Component: AdministradoresPage,
  },
  {
    path: '/donaciones',
    label: 'Donaciones',
    endpoint: 'donaciones',
    icon: Gift,
    Component: DonacionesPage,
  },
  {
    path: '/solicitudes',
    label: 'Solicitudes',
    endpoint: 'solicitudes',
    icon: ClipboardList,
    Component: SolicitudesPage,
  },
  {
    path: '/entregas',
    label: 'Entregas',
    endpoint: 'entregas',
    icon: Truck,
    Component: EntregasPage,
  },
  {
    path: '/acciones-importantes',
    label: 'Acciones importantes',
    endpoint: 'acciones-importantes',
    icon: Star,
    Component: AccionesPage,
  },
]
