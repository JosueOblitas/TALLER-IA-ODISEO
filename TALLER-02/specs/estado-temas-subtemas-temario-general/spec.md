# Estado de los temas y subtemas en el temario general

## Resumen ejecutivo

Se implementará un mecanismo de estados para temas y subtemas del temario general, permitiendo clasificarlos como Activos o Inactivos.

Los elementos inactivos no podrán utilizarse en procesos operativos como configuración de temarios personalizados, creación de syllabus, indexación de preguntas ni edición de atributos. Sin embargo, se mantendrá la persistencia histórica de la información previamente asociada a preguntas ya indexadas.

Cuando un tema sea desactivado, todos sus subtemas serán desactivados automáticamente. El sistema deberá advertir al administrador sobre los impactos del cambio mediante un modal de confirmación.

La solución busca evitar errores operativos derivados del uso de contenidos obsoletos y garantizar que únicamente los elementos vigentes puedan ser utilizados en nuevas configuraciones académicas.

---

# 1. Contexto de negocio (qué y por qué)

## Problema que resuelve

Actualmente los temas y subtemas pueden eliminarse o modificarse en el temario general sin un mecanismo que controle su disponibilidad operativa.

Esto provoca inconsistencias en:

Configuración de temarios personalizados.
Creación de syllabus.
Indexación de preguntas.
Edición de atributos de preguntas.

## Por qué ahora

El crecimiento del contenido académico incrementa el riesgo de utilizar estructuras desactualizadas o inválidas en procesos operativos críticos.

## Impacto esperado

Reducir errores de indexación.
Evitar configuraciones académicas inconsistentes.
Mantener trazabilidad histórica del contenido ya utilizado.
Mejorar el control administrativo sobre la vigencia de los temas.

---

# 2. User Stories y criterios de aceptación

## US-1 (P1)

Como Administrador,
quiero configurar estados para temas y subtemas,
para controlar su disponibilidad operativa dentro de la plataforma.

### AC-1.1

Dado un tema o subtema existente,
cuando el administrador edite su información,
entonces podrá seleccionar uno de los siguientes estados:

Activo
Inactivo

### AC-1.2

Dado un tema activo,
cuando el administrador lo cambie a Inactivo,
entonces todos sus subtemas asociados deberán pasar automáticamente a estado Inactivo.

---

## US-2 (P1)

Como Administrador,
quiero que los temarios personalizados utilicen únicamente elementos vigentes,
para evitar configuraciones basadas en contenido obsoleto.

### AC-2.1

Dado un tema inactivo,
cuando se configure un temario personalizado,
entonces dicho tema no deberá visualizarse para selección.

### AC-2.2

Dado un subtema inactivo,
cuando se configure un temario personalizado,
entonces dicho subtema no deberá visualizarse para selección.

---

## US-3 (P1)

Como Administrador,
quiero restringir la utilización de elementos inactivos en procesos académicos,
para asegurar consistencia operativa.

### AC-3.1

Dado un syllabus nuevo,
cuando se seleccione contenido académico,
entonces únicamente deberán mostrarse temas y subtemas activos.

### AC-3.2

Dado una pantalla de indexación de preguntas,
cuando se seleccione tema o subtema,
entonces solo deberán mostrarse elementos activos.

### AC-3.3

Dado una pantalla de edición de atributos de preguntas,
cuando se seleccione tema o subtema,
entonces solo deberán mostrarse elementos activos.

---

## US-4 (P1)

Como Administrador,
quiero preservar la información histórica de preguntas ya indexadas,
para evitar pérdida de trazabilidad académica.

### AC-4.1

Dado una pregunta asociada a un tema o subtema que posteriormente fue inactivado,
cuando se visualice la pregunta,
entonces deberá conservar la relación histórica existente.

### AC-4.2

Dado una pregunta indexada previamente,
cuando un tema o subtema cambie de estado,
entonces la información almacenada no deberá modificarse.

### AC-4.3

Dado una pregunta asociada a un elemento inactivo,
cuando se visualicen sus atributos,
entonces el tema o subtema deberá mostrarse en estado visual diferenciado (gris).

---

## US-5 (P2)

Como Administrador,
quiero recibir una advertencia antes de activar o desactivar elementos,
para comprender el impacto de la acción.

### AC-5.1

Dado un cambio de estado solicitado,
cuando el administrador confirme la operación,
entonces el sistema deberá mostrar un modal de advertencia indicando los impactos asociados.

### AC-5.2

Dado el modal de advertencia,
cuando el administrador cancele la acción,
entonces no deberá realizarse ningún cambio.

---

# 3. Requisitos no funcionales (NFR)

## NFR-1

La consulta de temas y subtemas deberá permitir filtrar por estado sin degradar el rendimiento perceptible de la interfaz.

## NFR-2

El cambio de estado deberá registrarse mediante auditoría existente del sistema.

## NFR-3

Las consultas utilizadas en indexación y configuración académica deberán excluir por defecto los registros inactivos.

## NFR-4

La persistencia histórica de preguntas indexadas debe mantenerse íntegra después de cualquier cambio de estado.

---

# 4. Casos borde

## CB-1

Desactivar un tema que posee subtemas activos.

Resultado esperado:
Todos los subtemas pasan automáticamente a estado Inactivo.

## CB-2

Intentar seleccionar un tema inactivo desde una pantalla que utiliza filtros activos.

Resultado esperado:
No debe visualizarse como opción seleccionable.

## CB-3

Visualizar una pregunta indexada con un tema actualmente inactivo.

Resultado esperado:
La asociación histórica se mantiene y se muestra en gris.

## CB-4

Existencia de temarios personalizados o syllabus que referencian elementos posteriormente inactivados.

Resultado esperado:
Mantener referencia histórica sin permitir nuevas selecciones.

---

# 5. Assumptions

## A-1

Se asume que actualmente los temas y subtemas no poseen un atributo de estado.

Si es falso, deberá evaluarse migración de datos.

## A-2

Se asume que la plataforma ya cuenta con infraestructura de auditoría para registrar cambios.

Si es falso, deberá incorporarse dentro del alcance técnico.

## A-3

Se asume que las preguntas almacenan referencias directas a temas y subtemas.

Si es falso, la estrategia de persistencia histórica deberá redefinirse.

---

# 6. Scope

## DENTRO

Gestión de estado Activo/Inactivo para temas.
Gestión de estado Activo/Inactivo para subtemas.
Impacto en temario personalizado.
Impacto en indexación de preguntas.
Impacto en edición de atributos.
Persistencia histórica.
Modal de advertencia.

## FUERA (explícito)

Eliminación física de temas o subtemas.
Cambios en la estructura jerárquica del temario.
Migraciones masivas de contenido académico.
Nuevos estados distintos a Activo e Inactivo.
Rediseño de procesos de syllabus fuera del filtrado por estado.