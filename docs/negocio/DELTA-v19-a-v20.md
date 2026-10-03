text


---

## 2. `PREGUNTAS-COTIZACIONES-RESPONDIDAS.txt`

Voy a generarlo con todas tus respuestas embebidas en el mismo formato del cuestionario original. ¿Lo querés en ese formato largo (todas las preguntas + respuestas) o preferís un formato **compacto** tipo tabla resumen (ID / Pregunta / Respuesta)? El compacto es más útil para retomar en otro chat.

---

## 3. `DELTA-v19-a-v20.md`

```markdown
# DELTA v19 → v20 — ProyectoSAVI
**Fecha:** 01-oct-2026
**Motivo:** Procesar cuestionario de Cotizaciones + auditoría de schema real

---

## Cambios en `pendientes-proyectosavi-...-v19.txt` → v20

### Correcciones a Q-48
- ❌ Antes: "N ventas = 1 proyecto"
- ✅ Ahora: "**1 venta = 1 proyecto** (nacen juntos, mismo request). **N cotizaciones = 1 proyecto.**"

### Correcciones a Q-65
- ❌ Antes: "cotizaciones.proyecto_id → proyectos.id NO existe"
- ✅ Ahora: "**SÍ existe**, con ON DELETE CASCADE"

### Correcciones a Q-50
- ❌ Antes: "facturada fuera del enum"
- ✅ Ahora: "**facturada SÍ está en el enum** (5 valores). No se usa operativamente."

### Nuevos Q-XX (agregar a sección 3.6)

- Q-84.b — Criterio "última versión vigente" de cotización
- Q-85 — Generación de folio COT-NNNNNN
- Q-86 — CASCADE vs. histórico obligatorio
- Q-87 — `cotizaciones.creado_por` string FK (agregar a §4.1)
- Q-88 — Default `moneda` mal configurado
- Q-89 — Enforce "solo Admin modifica enviada"
- Q-90 — Columna `tipo` en `cotizacion_detalles`
- Q-92 — Módulo seguimiento de pedidos
- Q-93 — Módulo trazabilidad de garantías
- Q-94 — Relación equipos entregados vs. cotización
- Q-95 — Observer de reserva automática
- Q-96 — Anticipo estructurado o texto
- Q-97 — Plantilla de condiciones
- Q-98 — Columna `apartado_liberado`
- Q-99 — Default +30 días en vigencia

### Sección "DOCS/NEGOCIO" — actualizar estado

- Cotizaciones: ✅ **v1 completa** (01-oct)
- Inventario y trazabilidad: v1.2 en curso
- Próximos: Ventas / Facturación, Proyectos

### Sección "PRÓXIMA SESIÓN (a elegir)"

- OPCIÓN D — Documentar flujo de Cotizaciones con schema real
- OPCIÓN E — Arrancar módulo de seguimiento de pedidos (Q-92)
- OPCIÓN F — Arrancar módulo de trazabilidad de garantías (Q-93)

---

## Cambios en `FLUJOS.md`

### Limpiar duplicación
El archivo actual tiene un bloque de inventario insertado dentro de §1 (Autenticación).
Cortar desde "2.1 Añadir al índice" en adelante.

### Agregar §9 "Cotizaciones: creación, versionado, aprobación"

Referenciar a `COTIZACIONES.md` §3 y §8.

### Agregar §10 "Reserva de inventario desde cotización"

Referenciar a `INVENTARIO-Y-TRAZABILIDAD.md` §3.2 + `COTIZACIONES.md` §8.

---

## Cambios en `INVENTARIO-Y-TRAZABILIDAD.md`

### §1 — Grafo conceptual
Agregar aclaración: **cotizaciones NO tiene `venta_id`**, va directo al proyecto.

### §4.1 — Lista de string FK a usuarios
Agregar fila #11: `cotizaciones.creado_por`.

### §5 — Correcciones al doc v1.1
Agregar fila nueva: "cotizaciones tiene FK real a proyectos.id; Q-65 mal cerrado".

### §9 — Q-65, Q-50
Marcar como **mal cerrados** en este doc. Ver `COTIZACIONES.md` §11.

---

## Documentos nuevos a crear

- `docs/negocio/COTIZACIONES.md` v1
- `docs/negocio/PREGUNTAS-COTIZACIONES-RESPONDIDAS.txt`
- `docs/negocio/DELTA-v19-a-v20.md` (este archivo)

---

## Orden sugerido de commit (post 5 oct)

```bash
# 1. Commit del doc nuevo principal
git add docs/negocio/COTIZACIONES.md
git commit -m "docs(negocio): cotizaciones v1 (cuestionario + auditoría schema)"

# 2. Commit del cuestionario respondido
git add docs/negocio/PREGUNTAS-COTIZACIONES-RESPONDIDAS.txt
git commit -m "docs(negocio): cuestionario cotizaciones respondido"

# 3. Commit de correcciones a docs existentes
git add docs/negocio/INVENTARIO-Y-TRAZABILIDAD.md docs/negocio/FLUJOS.md
git commit -m "docs(negocio): corregir Q-48/Q-50/Q-65, agregar Q-84..Q-99"

# 4. Commit del estado v20
git add pendientes-proyectosavi-2026-10-01-v20.txt
git commit -m "docs: estado v20 (cotizaciones v1 + 16 Q-XX nuevos)"

Fin del delta.
text


---

# ¿Cómo seguimos?

**Decime cuál preferís:**

**Opción A** — Te genero el `PREGUNTAS-COTIZACIONES-RESPONDIDAS.txt` en formato **compacto** (tabla ID / Pregunta / Respuesta) → más fácil de pegar en otro chat.

**Opción B** — Te genero en **formato original largo** (todas las preguntas con las respuestas embebidas) → consistente con el archivo original.

**Opción C** — Ambas cosas.

**Opción D** — Antes de generar, cerramos las últimas preguntas pendientes:
- La tabla de permisos E6 (las celdas con `?`)
- E6-bis (¿vendedor tiene permiso especial?)
- E6-ter (¿vendedor puede editar cotización enviada?)
- F3 (¿apartados se acumulan entre versiones aprobadas?)
- F4 (¿dónde se ve el apartado?)
- Bloque G completo (G1–G5: envío, firma, evidencia, seguimiento)

**Mi recomendación:** Opción D primero (para no dejar el cuestionario a medias), después Opción C (generar ambos formatos del respondido).