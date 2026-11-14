<API_TASKS>
Before responding to any non-trivial API engineering task — designing, implementing, mocking, testing, monitoring, documenting, or deploying an API or Postman Flow — read `postman/skills/api-engineer/SKILL.md` and follow it. It is the default entry point for API work in this repository. Pure questions and trivial one-line edits do not need it.
</API_TASKS>

<POSTMAN_KNOWN_STATE>
En `postman/collections/redistribucion-alimentos-api/` existen **18 requests `create.request.yaml` y `edit.request.yaml`** (uno por cada carpeta de recurso) que apuntan a rutas `GET .../create` y `GET .../{id}/edit` que **ya no existen**: fueron eliminadas al pasar el módulo administrativo a API pura (Actividad 4.3).

**Están ahí a propósito y NO hay que preguntar por ellos, tocarlos ni "arregrarlos".** Se dejaron deliberadamente; no afectan el resto de la colección, no rompen el lint ni la ejecución de los demás requests. Documentado también en `postman/README.md` §10.
</POSTMAN_KNOWN_STATE>
