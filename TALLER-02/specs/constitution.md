# Constitución Odiseo
 
 Guía de arquitectura y desarrollo para odiseo-backend y odiseo-frontend.
 
---

## Workflow prioritario

### 0. Descubrimiento obligatorio

Antes de proponer o implementar un cambio, buscar primero en `specs/**/spec.md`.

Si el cambio ya existe, revisar en este orden:

1. `specs/<cambio>/spec.md`
2. `specs/<cambio>/plan.md`
3. `specs/<cambio>/test-cases.md`

### 1. Estructura estándar de un cambio

Todo cambio debe vivir dentro de `specs/<cambio>/` y usar esta estructura:

| Ruta | Propósito |
|---|---|
| `specs/<cambio>/spec.md` | Contrato funcional: que debe hacer el sistema |
| `specs/<cambio>/plan.md` | Plan tecnico y operativo. Debe incluir una seccion obligatoria `## Tasks` |
| `specs/<cambio>/test-cases.md` | Casos de prueba y validacion del cambio |

### 2. Gates de validación (Checklist Ejecutable)

#### Gate 1: Spec (`spec.md`)
- [ ] Existe **Resumen ejecutivo** (qué y por qué, 3-5 líneas)
- [ ] Existe **Contexto de negocio**: Problema, Por qué ahora, Impacto esperado
- [ ] Existen **User Stories** (US-1, US-2...) con **AC** en formato **Given/When/Then**
- [ ] Cada AC usa estructura: **Dado / Cuando / Entonces**
- [ ] Existen **NFR** (no funcionales: rendimiento, auditoría, filtrado, persistencia)
- [ ] Existen **Casos borde** (CB-1, CB-2...) con resultado esperado
- [ ] Existen **Assumptions** (A-1, A-2...) con condición "Si es falso..."
- [ ] Existe **Scope**: **DENTRO** y **FUERA** explícitos
- [ ] Cero detalles de implementación técnica (no "localStorage", "API", "DB", "query", etc.)

#### Gate 2: Plan (`plan.md`)
- [ ] Existe **Why** (por qué) + **What Changes** (qué cambia)
- [ ] Existe **Capabilities**: New Capabilities y/o Modified Capabilities
- [ ] Existe **Tasks** con checkboxes `- [ ]`
- [ ] Cada task es accionable y testeable
- [ ] Existe **Impact** (UI, API, Persistencia, Reportes)

#### Gate 3: Test-Cases (`test-cases.md`)
- [ ] Agrupa casos por área (Modelo, Presentación, Flujo, etc.)
- [ ] Cada caso es un checkbox `- [ ]` con descripción clara
- [ ] Cubre **todos** AC de User Stories + NFR + Casos borde
- [ ] Casos son independientes y ejecutables

#### Gate 4: Implementation
- [ ] Los 3 gates anteriores ✅ → implementar
- [ ] Cualquier ❌ → **BLOQUEADO** hasta corregir

## Principios fundamentales
 
### I. Arquitectura hexagonal progresiva
 
El código nuevo del backend vive en src/. app/ es código legacy y solo se modifica cuando una funcionalidad existente lo requiere o existe una razón de migración documentada.
 
La separación de capas es obligatoria:
 
Domain — reglas de negocio, entidades, value objects, enums, contratos y excepciones.
Application — casos de uso, DTOs y orquestación.
Infrastructure — HTTP, persistencia, providers, adapters, validators, mappers y responses.
Shared — contratos y utilidades transversales.
Ninguna capa asume responsabilidades de otra.
 
### II. Contratos de negocio y persistencia
 
Cada acción de negocio se expresa mediante un Use Case. Todo Use Case nuevo es final, tiene una única responsabilidad y expone un único método público execute.
 
Los Use Cases reciben DTOs de entrada y retornan DTOs de salida cuando el resultado tiene significado de negocio. Pasar Request, modelos Eloquent, Query Builders o arrays sin tipado hacia Application o Domain está prohibido.
 
La persistencia cruza capas únicamente mediante interfaces o puertos de salida. Las implementaciones pueden usar Eloquent, SQL, Query Builder, APIs externas o storage, pero esos detalles no se filtran al dominio. Los controllers solo adaptan HTTP: validan, mapean, invocan Use Cases y serializan respuestas. Las entidades de dominio nunca se serializan directamente como JSON.
 
### III. Frontend modular por dominio
 
El frontend se organiza por módulos funcionales. src/modules concentra comportamiento de negocio visual: pantallas de módulo, dialogs, services, modelos, enums y componentes específicos. src/pages solo define rutas, metadata y carga la página de módulo correspondiente. Las páginas permanecen delgadas, declarativas y fáciles de revisar.
 
La lógica repetida se extrae a composables, services o stores según su alcance. No se crea una abstracción global antes de comprobar que resuelve duplicación real entre módulos.
 
### IV. Servicios, estado y permisos frontend
 
Las llamadas HTTP viven en services. Los componentes y páginas no construyen URLs manualmente si existe o debe existir un service de módulo. El cliente Axios compartido centraliza credenciales, base URL, interceptores y errores transversales.
 
Pinia gestiona el estado global o compartido. El estado local permanece en el componente o composable cuando no necesita compartirse.
 
CASL es la fuente de permisos del frontend. Las abilities del usuario se cargan desde almacenamiento local y se integran con guards y componentes. El frontend nunca reemplaza la autorización del backend; solo mejora la experiencia y evita acciones no permitidas.
 
### V. Calidad, errores y observabilidad
 
Los errores del backend se representan con excepciones tipadas de dominio, aplicación o infraestructura. Los Use Cases lanzan excepciones; no retornan errores como valores.
 
El mapeo HTTP debe ser consistente:
 
| Código | Significado |
|---|---|
| 401 | No autenticado |
| 403 | Sin permisos |
| 404 | Recurso inexistente |
| 409 | Conflicto de dominio |
| 422 | Error de validación |
| 500 | Error no controlado |
 
Las escrituras que requieran atomicidad usan la abstracción compartida de transacciones. La UI comunica estados de carga, error, vacío y éxito. Sentry se usa para observabilidad en backend y frontend. La claridad del contrato es más importante que reducir el número de clases o archivos. La respuesta API conserva el contrato { success, type, message, data } cuando aplica.
 
---
 
## Stack técnico
 
### Backend
 
| Categoría | Tecnologías |
|---|---|
| Lenguaje y framework | PHP 8.1, Laravel 10 |
| Autenticación | JWT / Sanctum |
| Colas y caché | Horizon, Redis |
| Documentos | mPDF / TCPDF, PHPWord, Excel |
| Storage | Google Cloud Storage |
| Pruebas | Pest |
| Análisis estático | PHPStan / Larastan |
| Observabilidad | Sentry |
 
### Frontend
 
| Categoría | Tecnologías |
|---|---|
| Framework y build | Vue 3, Vite |
| UI | Vuetify |
| Estado | Pinia |
| Permisos | CASL |
| Router | Vue Router (auto-generado desde src/pages) |
| HTTP | Axios |
| Utilidades | VueUse |
| Pruebas | Vitest |
| Calidad | ESLint, Prettier, Stylelint |
| Observabilidad | Sentry |
 
### Restricciones transversales
 
Las rutas backend nuevas siguen el versionado existente, especialmente v2 para módulos nuevos o migrados.
Cada ruta backend protegida declara autenticación y permisos de forma explícita.
Los providers de infraestructura registran dependencias mediante el contenedor de Laravel.
Los mappers transforman datos; no contienen reglas de negocio.
Los validators pertenecen a infraestructura HTTP; no sustituyen invariantes del dominio.
Las entidades y value objects protegen sus invariantes al construirse o modificarse.
Los DTOs documentan contratos entre capas y no se convierten en contenedores ambiguos.
Las rutas frontend se generan desde src/pages y se enriquecen con metadata mediante definePage.
Los aliases de Vite (@, @core, @layouts, @images, @styles) se prefieren sobre rutas relativas largas.
Los assets visuales viven en assets/ o public/ según su uso. Los estilos globales se mantienen en archivos compartidos; los estilos locales permanecen en el componente.
---
 
## Flujo de desarrollo
 
### Antes de implementar: tabla de decisión rápida
 
| Naturaleza del cambio | Destino |
|---|---|
| Regla de negocio, invariante, entidad | Domain — entidad, value object, excepción |
| Orquestación de pasos o flujo | Application — Use Case, DTO |
| HTTP, BD, API externa, storage | Infrastructure — adapter, repository, mapper |
| Funcionalidad backend nueva | src/App/{Módulo} — nunca app/ sin razón documentada |
| Lógica visual de módulo | src/modules/{módulo} — composable, service, store |
| Punto de entrada de ruta | src/pages — delgado y declarativo |
| Código legacy existente | app/ — solo si la funcionalidad ya vive ahí |
 
### Backend
 
1. Ubicar si el cambio pertenece a legacy (app/) o a la arquitectura nueva (src/).
2. Si es funcionalidad nueva, crear o extender un módulo bajo src/App, src/App/Client o src/App/Partners.
3. Definir primero los contratos: entrada, salida, repositorio y excepciones necesarias.
4. Implementar dominio antes de infraestructura cuando la regla de negocio sea relevante.
5. Mantener controllers pequeños y sin decisiones de negocio.
### Frontend
 
1. Identificar si el cambio pertenece a página, módulo, componente compartido, composable, service o store.
2. Crear la lógica en el nivel más local posible.
3. Moverla a composable o store solo cuando exista reutilización o estado compartido real.
4. Mantener la página como punto de entrada del router, no como contenedor de funcionalidad.
5. Encapsular nuevas llamadas API en services y reutilizar odiseoApi.
### Pruebas y calidad
 
Agregar tests proporcionales al riesgo del cambio.
 
**Pest** es el estándar de pruebas backend. Los tests siguen estructura por contexto, usan builders cuando aplica y mockean interfaces en pruebas de aplicación. Todo test backend nuevo usa declare(strict_types=1).
**Vitest** cubre cambios frontend que afecten transformaciones, composables, stores o flujos con riesgo.
Ejecutar lint, format, pruebas o análisis estático cuando el cambio toque: contratos, persistencia, permisos, serialización, UI visible o carga asíncrona.
 
### Anti-patrones
 
Estos patrones violan la constitución y deben rechazarse en revisión de código:
 
| Anti-patrón | Violación | Corrección |
|---|---|---|
| Pasar Request a un Use Case | Separación de capas | Mapear a DTO en el controller antes de invocar el Use Case |
| Retornar un modelo Eloquent desde un Use Case | Contratos de negocio | Mapear a DTO de salida en Application o Infrastructure |
| Lógica de negocio en un controller | Responsabilidad única | Mover al Use Case o al Domain correspondiente |
| Construir URLs en un componente Vue | Servicios frontend | Centralizar en un service de módulo que use odiseoApi |
| Usar app/ para funcionalidad nueva sin documentar | Flujo de desarrollo | Crear el módulo en src/App/ y documentar si hay razón de migración |
| Store global para estado que solo usa un componente | Estado local primero | Mantener en el componente o en un composable local |
| Romper un contrato de ruta sin versionar | Restricciones técnicas | Crear ruta v{n+1} o documentar la migración explícitamente |
 
---
 
## Gobernanza
 
Esta constitución gobierna todo cambio nuevo en odiseo-backend y odiseo-frontend. Las ADR aceptadas tienen autoridad para detallar decisiones específicas. Los patrones existentes en ambos repositorios tienen prioridad sobre estilos nuevos no justificados. Cuando un patrón se repite entre módulos o proyectos, debe promoverse a ADR, convención documentada o enmienda de esta constitución.
 
Toda excepción debe documentar **motivo, riesgo, alcance y plan de corrección** antes de ser implementada.
 
Las revisiones de código verifican: capas, contratos, errores, transacciones, permisos, servicios, estados de UI, consistencia visual y pruebas.
 
La arquitectura puede evolucionar, pero debe preservar: fronteras claras entre capas, páginas delgadas, servicios explícitos, composables reutilizables y contratos testeables.
 
### Reglas absolutas — nunca se rompen
 
Los Use Cases son final con un único método público execute.
Modelos Eloquent, Request y arrays sin tipado nunca entran a Application o Domain.
Las entidades de dominio no se serializan directamente como JSON.
Cada ruta backend protegida declara autenticación y permisos explícitos.
El frontend no reemplaza la autorización del backend.
Todo test backend nuevo usa declare(strict_types=1).
### Reglas preferidas — excepción con ADR y plan de corrección
 
Código nuevo en src/; app/ solo para legacy con razón documentada.
Los mappers no contienen reglas de negocio.
Las páginas frontend permanecen delgadas y sin lógica de negocio.
No se crean abstracciones globales antes de verificar duplicación real entre módulos.
Se ejecutan lint, format y pruebas antes de merges que toquen contratos o persistencia.
---
 
**Versión**: 1.1.0 | **Ratificada**: 2026-06-15 | **Última enmienda**: 2026-06-15
