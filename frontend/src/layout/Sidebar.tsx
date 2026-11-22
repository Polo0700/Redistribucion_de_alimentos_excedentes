import { NavLink } from 'react-router-dom'
import { MENU } from '../routes/menu'

interface SidebarProps {
  // Cierra el drawer en movil al navegar.
  onNavigate?: () => void
}

export function Sidebar({ onNavigate }: SidebarProps) {
  return (
    <div className="flex h-full w-64 flex-col border-r border-gray-200 bg-white">
      <div className="flex h-16 shrink-0 items-center border-b border-gray-200 px-5">
        <span className="text-base font-semibold tracking-tight text-gray-900">
          Redistribucion de alimentos
        </span>
      </div>

      <nav className="flex-1 overflow-y-auto px-3 py-4">
        <p className="px-3 pb-2 text-xs font-medium uppercase tracking-wider text-gray-400">
          Modulo administrativo
        </p>

        <ul className="space-y-1">
          {MENU.map(({ path, label, icon: Icon }) => (
            <li key={path}>
              <NavLink
                to={path}
                onClick={onNavigate}
                className={({ isActive }) =>
                  [
                    'flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors',
                    isActive
                      ? 'bg-blue-50 font-medium text-blue-700'
                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900',
                  ].join(' ')
                }
              >
                <Icon size={18} strokeWidth={1.8} aria-hidden="true" />
                <span className="truncate">{label}</span>
              </NavLink>
            </li>
          ))}
        </ul>
      </nav>

      <div className="shrink-0 border-t border-gray-200 px-5 py-4 text-xs text-gray-400">
        Actividad 5.1
      </div>
    </div>
  )
}
