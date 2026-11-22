import { Navigate, Route, Routes } from 'react-router-dom'
import { AdminLayout } from './layout/AdminLayout'
import { MENU } from './routes/menu'

// Las rutas se generan desde MENU (routes/menu.ts), la fuente unica
// de verdad. Agregar un recurso = agregar una entrada en MENU.
export default function App() {
  return (
    <Routes>
      <Route element={<AdminLayout />}>
        <Route path="/" element={<Navigate to={MENU[0].path} replace />} />

        {MENU.map(({ path, Component }) => (
          <Route key={path} path={path} element={<Component />} />
        ))}

        <Route
          path="*"
          element={
            <div className="mx-auto max-w-xl rounded-xl border border-gray-200 bg-white p-10 text-center">
              <h2 className="text-lg font-semibold text-gray-900">Pagina no encontrada</h2>
              <p className="mt-2 text-sm text-gray-500">
                La ruta solicitada no existe en el menu administrativo.
              </p>
            </div>
          }
        />
      </Route>
    </Routes>
  )
}
