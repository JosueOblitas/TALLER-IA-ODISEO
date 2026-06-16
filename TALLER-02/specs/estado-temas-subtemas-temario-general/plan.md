## Why

El temario general no ofrece una forma clara de conocer qué temas y subtemas están pendientes, en progreso o completados. Sin ese estado, el seguimiento del avance depende de revisión manual y se vuelve dificil identificar bloqueos y prioridades.

## What Changes

- Añade un estado por tema y subtema dentro del temario general.
- Muestra una vista consolidada con el progreso del temario y el avance por rama.
- Permite actualizar el estado de cada nodo del temario.
- Calcula el avance agregado de los temas padre a partir de sus subtemas.

## Capabilities

### New Capabilities
- `temario-general-status`: seguimiento, consulta y actualización del estado de temas y subtemas del temario general.

### Modified Capabilities
- 

## Tasks

- Definir la estructura del estado por tema y subtema.
- Alinear la consulta jerarquica y el calculo agregado con la spec.
- Verificar la cobertura de validacion antes de implementar.

## Impact

- UI para visualizar el temario y su progreso.
- API para consultar y actualizar estados.
- Persistencia del estado por nodo del temario.
- Cualquier reporte o tablero que consuma el avance general.
