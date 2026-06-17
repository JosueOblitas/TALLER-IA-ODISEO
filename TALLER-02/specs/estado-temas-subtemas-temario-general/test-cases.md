# Casos de prueba — Estado de temas y subtemas en el temario general

## Convenciones

| Etiqueta | Significado |
|---|---|
| `[US-1]` | User Story 1 — Configurar estados |
| `[US-2]` | User Story 2 — Temarios personalizados |
| `[US-3]` | User Story 3 — Restricción en procesos |
| `[US-4]` | User Story 4 — Persistencia histórica |
| `[US-5]` | User Story 5 — Modal de advertencia |
| `[CB]` | Caso borde |
| `[NFR]` | Requisito no funcional |
| `[CONST]` | Regla de la constitución |
| `[DOM]` | Prueba de dominio (entidad, value object) |
| `[API]` | Prueba de API/controller |
| `[UI]` | Prueba de componente frontend |
| `[INT]` | Prueba de integración |

---

## 1. Modelo y contratos (dominio)

### 1.1 Creación y cambio de estado

- [ ] **[DOM-01]** Dado un tema sin estado, cuando se crea, entonces su estado predeterminado debe ser `Activo`.
- [ ] **[DOM-02]** Dado un subtema sin estado, cuando se crea, entonces su estado predeterminado debe ser `Activo`.
- [ ] **[DOM-03]** Dado un tema con estado `Activo`, cuando se cambia a `Inactivo`, entonces el cambio debe persistirse correctamente.
- [ ] **[DOM-04]** Dado un subtema con estado `Activo`, cuando se cambia a `Inactivo`, entonces el cambio debe persistirse correctamente.
- [ ] **[DOM-05]** Dado un tema con estado `Inactivo`, cuando se cambia a `Activo`, entonces el cambio debe persistirse correctamente.
- [ ] **[DOM-06]** Dado un tema o subtema, cuando se intenta asignar un estado distinto de `Activo` o `Inactivo`, entonces el sistema debe rechazar la operación con un error de validación.

### 1.2 Propagación de estado padre → hijo

- [ ] **[DOM-07]** Dado un tema activo con subtemas activos, cuando el tema se desactiva, entonces todos sus subtemas deben pasar a `Inactivo` automáticamente.
- [ ] **[DOM-08]** Dado un tema activo con subtemas mixtos (activos e inactivos), cuando el tema se desactiva, entonces todos los subtemas permanecen `Inactivo`.
- [ ] **[DOM-09]** Dado un tema inactivo con subtemas inactivos, cuando se reactiva el tema, entonces los subtemas **no** deben reactivarse automáticamente; deben permanecer `Inactivo`.
- [ ] **[DOM-10]** Dado un tema sin subtemas, cuando se desactiva, entonces la operación debe completarse sin errores.
- [ ] **[DOM-11]** Dado un tema con subtemas anidados en múltiples niveles (sub-subtemas), cuando el tema raíz se desactiva, entonces todos los descendientes deben pasar a `Inactivo`.

---

## 2. API / Controladores

### 2.1 Consulta de temas y subtemas

- [ ] **[API-01]** Dada una petición GET a la ruta del temario general, cuando se invoca sin filtros, entonces debe retornar todos los temas con su estado y progreso agregado.
- [ ] **[API-02]** Dada una petición GET con filtro `?status=activo`, cuando se invoca, entonces debe retornar solo los elementos con estado `Activo`.
- [ ] **[API-03]** Dada una petición GET con filtro `?status=inactivo`, cuando se invoca, entonces debe retornar solo los elementos con estado `Inactivo`.
- [ ] **[API-04]** Dada una petición GET con filtro inválido `?status=unknown`, entonces debe responder con `422` y un mensaje de error de validación.
- [ ] **[API-05]** Dada una petición GET sin autenticación, entonces debe responder con `401`.

### 2.2 Actualización de estado

- [ ] **[API-06]** Dada una petición PUT/PATCH al estado de un tema o subtema con payload `{ "status": "inactivo" }`, cuando el usuario tiene permisos, entonces debe responder con `200` y el estado actualizado.
- [ ] **[API-07]** Dada una petición de actualización con payload `{ "status": "" }`, entonces debe responder con `422`.
- [ ] **[API-08]** Dada una petición de actualización con payload `{ "status": "inexistente" }`, entonces debe responder con `422`.
- [ ] **[API-09]** Dada una petición de actualización para un tema que no existe, entonces debe responder con `404`.
- [ ] **[API-10]** Dada una petición de actualización sin autenticación, entonces debe responder con `401`.
- [ ] **[API-11]** Dada una petición de actualización sin permisos de administrador, entonces debe responder con `403`.
- [ ] **[API-12]** Dada una petición de actualización que desactiva un tema con subtemas, entonces la respuesta debe incluir los subtemas afectados que también fueron desactivados.
- [ ] **[API-13]** Dada una petición de actualización con `Content-Type` incorrecto, entonces debe responder con `415` o `422`.

### 2.3 Contrato de respuesta

- [ ] **[API-14]** Dada una respuesta exitosa, entonces debe usar el formato `{ success: true, type: "...", message: "...", data: [...] }` según la constitución.
- [ ] **[API-15]** Dada una respuesta de error, entonces debe usar el formato `{ success: false, type: "error", message: "...", data: null }`.
- [ ] **[API-16]** Dada una respuesta con datos de temario, entonces cada nodo debe incluir `id`, `nombre`, `status`, `progreso` y lista de `subtemas`.
- [ ] **[API-17]** Dado un cambio de estado, entonces la respuesta debe incluir metadatos de auditoría (usuario, timestamp).

---

## 3. Persistencia y repositorio

- [ ] **[INT-01]** Dado un cambio de estado exitoso, cuando se consulta la base de datos directamente, entonces el registro debe reflejar el nuevo estado.
- [ ] **[INT-02]** Dado un cambio de estado que desactiva un tema, cuando se consultan los subtemas en BD, entonces todos deben tener `status = inactivo`.
- [ ] **[INT-03]** Dado un tema inactivo con preguntas indexadas históricamente, cuando se consulta la pregunta, entonces la relación con el tema debe conservarse.
- [ ] **[INT-04]** Dado un cambio de estado, entonces debe existir un registro de auditoría con usuario, fecha, estado anterior y estado nuevo.
- [ ] **[INT-05]** Dado un rol sin permisos de administración, cuando se intenta actualizar el estado vía API, entonces el repositorio no debe ejecutar la escritura.

---

## 4. UI — Vista del temario general

### 4.1 Visualización de estado

- [ ] **[UI-01]** Dado un tema o subtema activo, entonces en la vista debe mostrarse con un indicador visual de "Activo" (ej. icono verde, badge).
- [ ] **[UI-02]** Dado un tema o subtema inactivo, entonces en la vista debe mostrarse con un indicador visual de "Inactivo" (ej. icono gris, badge).
- [ ] **[UI-03]** Dado el temario general, entonces cada tema debe mostrar una barra de progreso que represente la proporción de subtemas activos.
- [ ] **[UI-04]** Dado un tema sin subtemas, entonces la barra de progreso debe mostrar 0% o 100% según el estado del tema.
- [ ] **[UI-05]** Dado un tema con subtemas, entonces la barra de progreso debe reflejar el cálculo agregado correcto.

### 4.2 Actualización desde la UI

- [ ] **[UI-06]** Dado un tema o subtema visible, cuando el administrador hace clic en "Desactivar", entonces debe aparecer un modal de confirmación (ver sección 6).
- [ ] **[UI-07]** Dado un modal de confirmación aceptado, entonces la UI debe reflejar el nuevo estado del nodo sin recargar la página.
- [ ] **[UI-08]** Dado un modal de confirmación cancelado, entonces la UI no debe cambiar el estado.
- [ ] **[UI-09]** Dado un cambio de estado exitoso, entonces la barra de progreso del padre debe actualizarse automáticamente.
- [ ] **[UI-10]** Dado un error de red al actualizar, entonces la UI debe mostrar un mensaje de error y mantener el estado anterior.
- [ ] **[UI-11]** Dado un usuario sin permisos, entonces el botón/toggle de cambio de estado no debe estar visible o debe estar deshabilitado.

---

## 5. Procesos académicos — Filtrado por estado

### 5.1 Temario personalizado (US-2)

- [ ] **[US-2-01]** Dado un temario personalizado en creación, cuando se listan temas disponibles, entonces no deben aparecer temas con estado `Inactivo`.
- [ ] **[US-2-02]** Dado un temario personalizado en creación, cuando se listan subtemas disponibles, entonces no deben aparecer subtemas con estado `Inactivo`.
- [ ] **[US-2-03]** Dado un temario personalizado existente que incluye un tema posteriormente inactivado, cuando se edita, entonces el tema debe mostrarse como no seleccionable o con indicador visual de inactivo.
- [ ] **[US-2-04]** Dado un temario personalizado existente, cuando se intenta agregar un tema inactivo, entonces el sistema debe rechazar la operación.

### 5.2 Syllabus (US-3 / AC-3.1)

- [ ] **[US-3-01]** Dado un syllabus nuevo, cuando se selecciona contenido académico, entonces solo deben mostrarse temas y subtemas activos.
- [ ] **[US-3-02]** Dado un syllabus existente que referencia un tema inactivo, entonces la referencia histórica debe mantenerse pero el tema debe mostrarse en estado visual diferenciado (gris).

### 5.3 Indexación de preguntas (US-3 / AC-3.2)

- [ ] **[US-3-03]** Dada la pantalla de indexación de preguntas, cuando se selecciona tema o subtema, entonces solo deben mostrarse elementos activos.
- [ ] **[US-3-04]** Dada la indexación de una pregunta nueva, cuando se intenta asociar a un tema inactivo, entonces el sistema debe rechazar la operación.

### 5.4 Edición de atributos de preguntas (US-3 / AC-3.3)

- [ ] **[US-3-05]** Dada la edición de atributos de una pregunta, cuando se selecciona tema o subtema, entonces solo deben mostrarse elementos activos.
- [ ] **[US-3-06]** Dada la edición de atributos de una pregunta indexada, cuando el tema asociado está inactivo, entonces debe mostrarse con indicador visual gris pero la edición debe permitirse.

---

## 6. Modal de advertencia (US-5)

- [ ] **[US-5-01]** Dado un cambio de estado a `Inactivo` en un tema sin subtemas, entonces el modal debe indicar que solo el tema será afectado.
- [ ] **[US-5-02]** Dado un cambio de estado a `Inactivo` en un tema con subtemas activos, entonces el modal debe advertir que todos los subtemas también serán desactivados.
- [ ] **[US-5-03]** Dado un cambio de estado a `Inactivo` en un tema referenciado en temarios personalizados o syllabus, entonces el modal debe listar los impactos (cantidad de temarios/syllabus afectados).
- [ ] **[US-5-04]** Dado un cambio de estado a `Activo` en un tema, entonces el modal debe confirmar la acción sin advertencias adicionales (o indicar que no hay impactos negativos).
- [ ] **[US-5-05]** Dado el modal de advertencia visible, cuando el administrador hace clic en "Cancelar", entonces no debe realizarse ningún cambio de estado.
- [ ] **[US-5-06]** Dado el modal de advertencia visible, cuando el administrador hace clic en "Confirmar", entonces el cambio de estado debe ejecutarse.
- [ ] **[US-5-07]** Dado el modal de advertencia, entonces debe tener un botón de cierre (X) que cancele la operación.

---

## 7. Persistencia histórica (US-4)

- [ ] **[US-4-01]** Dada una pregunta indexada asociada a un tema activo, cuando el tema se desactiva, entonces la pregunta debe conservar la asociación histórica.
- [ ] **[US-4-02]** Dada una pregunta indexada previamente, cuando su tema cambia de estado, entonces los datos almacenados de la pregunta no deben modificarse.
- [ ] **[US-4-03]** Dada una pregunta asociada a un tema inactivo, cuando se visualizan sus atributos, entonces el tema debe mostrarse en gris pero la información debe ser visible.
- [ ] **[US-4-04]** Dada una pregunta asociada a un tema inactivo, cuando se exporta un reporte, entonces el nombre del tema debe aparecer correctamente (no como "null" o vacío).
- [ ] **[US-4-05]** Dado un tema que fue desactivado y luego reactivado, cuando se consultan las preguntas históricas, entonces estas deben seguir asociadas correctamente.

---

## 8. Casos borde (CB)

- [ ] **[CB-01]** Desactivar un tema que posee subtemas activos → todos los subtemas pasan a `Inactivo`.
- [ ] **[CB-02]** Intentar seleccionar un tema inactivo desde una pantalla con filtro activo → no debe visualizarse como opción seleccionable.
- [ ] **[CB-03]** Visualizar una pregunta indexada con un tema actualmente inactivo → la asociación histórica se mantiene y se muestra en gris.
- [ ] **[CB-04]** Existencia de temarios personalizados o syllabus que referencian elementos posteriormente inactivados → mantener referencia histórica sin permitir nuevas selecciones.
- [ ] **[CB-05]** Desactivar y reactivar un tema múltiples veces → el estado debe alternar correctamente en cada operación.
- [ ] **[CB-06]** Desactivar un subtema cuyo tema padre está inactivo → el subtema ya debe estar inactivo; la operación debe ser idempotente.
- [ ] **[CB-07]** Reactivar un subtema cuyo tema padre sigue inactivo → el sistema debe rechazar la operación (un subtema no puede estar activo si su padre está inactivo).
- [ ] **[CB-08]** Concurrencia: dos administradores intentan cambiar el estado del mismo tema simultáneamente → el sistema debe manejar el conflicto (ej. con optimistic locking) y no sobrescribir silenciosamente.
- [ ] **[CB-09]** Tema con gran cantidad de subtemas (+1000) al desactivarse → la propagación debe ser eficiente sin timeout ni bloqueo prolongado.
- [ ] **[CB-10]** Temario personalizado que solo contiene temas inactivos → debe mostrarse vacío o con mensaje indicativo, no romper la UI.

---

## 9. Regresión

- [ ] **[REG-01]** Dado un flujo completo de creación de temario personalizado, después de implementar el filtro por estado, el flujo debe seguir funcionando correctamente.
- [ ] **[REG-02]** Dado un flujo completo de indexación de preguntas, después de implementar el filtro por estado, el flujo debe seguir funcionando correctamente.
- [ ] **[REG-03]** Dado un flujo de edición de atributos de pregunta, después de implementar el estado visual gris, la edición debe seguir funcionando.
- [ ] **[REG-04]** Dados los reportes existentes que consumen el temario, después del cambio, los reportes deben incluir el estado pero no romperse.

