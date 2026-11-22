import { useState } from 'react'
import { Outlet, useLocation } from 'react-router-dom'
import { Menu, X } from 'lucide-react'
import { Sidebar } from './Sidebar'
import { MENU } from '../routes/menu'

// Estructura general del panel: barra lateral fija, cabecera con el
// titulo del recurso activo y area de contenido con <Outlet/>.
export function AdminLayout() {
  const [abierto, setAbierto] = useState(false)
  const { pathname } = useLocation()
  const activo = MENU.find((item) => item.path === pathname)

  return (
    <div className="flex min-h-screen bg-gray-50">
      {/* Barra lateral en escritorio */}
      <aside className="hidden lg:block">
        <Sidebar />
      </aside>

      {/* Barra lateral en movil (drawer) */}
      {abierto && (
        <div className="fixed inset-0 z-40 lg:hidden">
          <div
            className="absolute inset-0 bg-black/40"
            onClick={() => setAbierto(false)}
            aria-hidden="true"
          />
          <aside className="absolute inset-y-0 left-0 shadow-xl">
            <div className="relative h-full">
              <Sidebar onNavigate={() => setAbierto(false)} />
              <button
                type="button"
                onClick={() => setAbierto(false)}
                aria-label="Cerrar menu"
                className="absolute right-3 top-4 rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-700"
              >
                <X size={18} />
              </button>
            </div>
          </aside>
        </div>
      )}

      <div className="flex min-w-0 flex-1 flex-col">
        <header className="flex h-16 shrink-0 items-center gap-4 border-b border-gray-200 bg-white px-4 sm:px-6">
          <button
            type="button"
            onClick={() => setAbierto(true)}
            aria-label="Abrir menu"
            className="rounded-lg p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 lg:hidden"
          >
            <Menu size={20} />
          </button>

          <h1 className="truncate text-base font-semibold text-gray-900 sm:text-lg">
            {activo?.label ?? 'Panel administrativo'}
          </h1>

          {activo && (
            <code className="ml-auto hidden truncate rounded-md bg-gray-100 px-2.5 py-1 text-xs text-gray-600 md:block">
              GET /api/v1/admin/{activo.endpoint}
            </code>
          )}
        </header>

        <main className="flex-1 p-4 sm:p-6">
          <Outlet />
        </main>
      </div>
    </div>
  )
}
