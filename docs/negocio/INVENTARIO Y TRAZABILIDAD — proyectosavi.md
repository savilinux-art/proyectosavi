markdown

# INVENTARIO Y TRAZABILIDAD — proyectosavi

**Versión:** 1.2 (borrador)
**Fecha:** 01-oct-2026
**Base:** v1.1 + auditoría de schema en vivo (sesión 30-sep/01-oct 2026)
**Estado:** Grafo de FKs cerrado. Pendientes Q-53 y Q-56 (no bloqueantes).

---

## §0. Nota metodológica (leer antes que nada)

La auditoría inicial que originó esta revisión se hizo contra un **dump truncado**
exportado desde phpMyAdmin. El dump cortó blobs grandes (`ventas.cotizacion` y
`ventas.levantamiento` son `mediumblob` — causa probable del truncamiento), lo que
produjo **al menos 3 hallazgos críticos falsos**:

| Hallazgo del audit original | Realidad verificada en vivo |
|---|---|
| H1: `ventas` no existe | ❌ Falso. Existe, con FK a `usuarios.usuario` y UNIQUE en `nombre_proyecto`. |
| H9: `instalaciones.estatus_instalacion` sin FK | ❌ Falso. Tiene FK a `estatus.estatus`. |
| H4: `inventario.categoria` sin FK | ❌ Falso. Tiene FK a `categorias.nombre_categoria`. |
| H3/H10: referencias por string a proyecto | 🟡 Matizado. Son FK reales, usan `nombre_proyecto` como clave natural (no `id`). |

**Regla operativa:** no declarar "FK faltante" sin verificar contra
`information_schema.KEY_COLUMN_USAGE` en la BD en vivo.

**Distinción de deuda:** una clave natural inmutable (string estable por regla de
negocio) **no es antipatrón**. La regla "no se renombran proyectos" convierte a
`nombre_proyecto` en identificador estable, no en deuda técnica.

---

## §1. Modelo conceptual confirmado

ventas ──(nombre_proyecto UNIQUE, inmutable)──> proyectos
│
┌────────────────────────┼────────────────────────┐
│ │ │
(por FK, string) (por FK, string) (por FK, string)
│ │ │
movimientos_inventario salidas_inventario devoluciones_inventario
│
instalaciones
│
(por convención, SIN FK)
│
cotizaciones ──> proyectos.id
text


**Reglas del modelo:**

1. `ventas.nombre_proyecto` es el **origen canónico** del nombre del proyecto.
   UNIQUE. Inmutable por regla de negocio ("no se renombran proyectos").
2. **Todo proyecto nace de una venta.** Enforced por
   `proyectos.nombre_proyecto → ventas.nombre_proyecto`.
   Verificado en vivo: 0 proyectos sin venta.
3. **Convención operativa:** lo que se mueve, entrega, devuelve, instala y cotiza
   apunta a `proyectos` por nombre.
4. **Excepción:** `cotizaciones.proyecto_id → proyectos.id` (PK numérico).
   Motivación: cotización es documento formal.
   ⚠️ **Deuda:** esta FK **no existe como constraint** (ver Q-65).

---

## §2. Cambios aplicados a la BD (sesión 30-sep/01-oct)

### 2.1 `movimientos_inventario` — consolidación de FK

- Detectada **doble FK**: a `proyectos` y a `ventas`. Redundante y sobre-restringida.
- **Dropeada:** `movimientos_inventario_instalacion_foreign` (→ ventas).
- **Conservada:** `fk_mov_proyecto` → `proyectos.nombre_proyecto`.
- Índice huérfano verificado: ninguno.
- ⚠️ **Residuo:** la columna sigue llamándose `instalacion` (ver Q-67).

### 2.2 Unificación de dirección de FKs (Opción A)

Se migraron 3 tablas de apuntar a `ventas` → apuntar a `proyectos`:

| Tabla | Constraint nueva | Estado |
|---|---|---|
| `salidas_inventario` | `fk_salidas_proyecto → proyectos.nombre_proyecto` | ✅ |
| `devoluciones_inventario` | `fk_devoluciones_proyecto → proyectos.nombre_proyecto` | ✅ |
| `instalaciones` | `fk_instalaciones_proyecto → proyectos.nombre_proyecto` | ✅ |

- Sin índices residuales, sin referencias a `ventas`.
- **Nullability asimétrica:** `devoluciones_inventario.nombre_proyecto` es **nullable**;
  las otras tres **no**. Pendiente de justificar o corregir (Q-68).

### 2.3 Verificación previa

```sql
ALTER TABLE proyectos ADD UNIQUE KEY proyectos_nombre_unique (nombre_proyecto);

Ya existía (proyectos_nombre_unique, Non_unique=0). Confirmado.
§3. Grafo de FKs completo (29/29)

Total verificado: 29 FKs en proyectosavi. Listado íntegro:
#	Tabla	Columna	Constraint	→ Tabla	→ Columna
1	certificado_historial	recordatorio_id	certificado_historial_recordatorio_id_foreign	recordatorios	id
2	certificado_historial	usuario_id	certificado_historial_usuario_id_foreign	usuarios	id
3	devoluciones_inventario	devuelto_por	devoluciones_inventario_devuelto_por_foreign	usuarios	usuario
4	devoluciones_inventario	recibido_por	devoluciones_inventario_recibido_por_foreign	usuarios	usuario
5	devoluciones_inventario	nombre_proyecto	fk_devoluciones_proyecto	proyectos	nombre_proyecto
6	instalaciones	nombre_proyecto	fk_instalaciones_proyecto	proyectos	nombre_proyecto
7	instalaciones	estatus_instalacion	instalaciones_estatus_instalacion_foreign	estatus	estatus
8	instalaciones	id_usuario_asignado	instalaciones_id_usuario_asignado_foreign	usuarios	usuario
9	instalacion_fotos	instalacion_id	instalacion_fotos_instalacion_id_foreign	instalaciones	id
10	inventario	categoria	inventario_categoria_foreign	categorias	nombre_categoria
11	inventario	modificado_por	inventario_modificado_por_foreign	usuarios	usuario
12	movimientos_inventario	instalacion	fk_mov_proyecto	proyectos	nombre_proyecto
13	movimientos_inventario	inventario_id	movimientos_inventario_inventario_id_foreign	inventario	id
14	movimientos_inventario	modificado_por	movimientos_inventario_modificado_por_foreign	usuarios	usuario
15	notificaciones	usuario_id	notificaciones_usuario_id_foreign	usuarios	usuario
16	notificaciones_web	recordatorio_id	notificaciones_web_recordatorio_id_foreign	recordatorios	id
17	notificaciones_web	usuario_id	notificaciones_web_usuario_id_foreign	usuarios	id
18	permiso_rol	permiso_id	permiso_rol_permiso_id_foreign	permisos	id
19	permiso_rol	rol	permiso_rol_rol_foreign	roles	rol
20	proyectos	modificado_por	proyectos_modificado_por_foreign	usuarios	usuario
21	proyectos	nombre_proyecto	proyectos_nombre_proyecto_foreign	ventas	nombre_proyecto
22	recordatorios	created_by	recordatorios_created_by_foreign	usuarios	id
23	recordatorios	usuario_id	recordatorios_usuario_id_foreign	usuarios	id
24	salidas_inventario	nombre_proyecto	fk_salidas_proyecto	proyectos	nombre_proyecto
25	salidas_inventario	entregado_a	salidas_inventario_entregado_a_foreign	usuarios	usuario
26	salidas_inventario	entregado_por	salidas_inventario_entregado_por_foreign	usuarios	usuario
27	telescope_entries_tags	entry_uuid	telescope_entries_tags_entry_uuid_foreign	telescope_entries	uuid
28	usuarios	rol	usuarios_rol_foreign	roles	rol
29	ventas	vendedor	ventas_vendedor_foreign	usuarios	usuario
3.1 Observaciones del grafo

    cotizaciones NO aparece. No tiene ninguna FK (ver Q-65).

    ventas es raíz: solo proyectos.nombre_proyecto → ventas.nombre_proyecto
    la referencia. Nadie la apunta por PK.

    telescope_entries_tags presente → Telescope activo con schema poblado (Q-60).

    No hay FK a almacenes en ninguna tabla → modelo de almacenes no enforced (Q-56).

    No hay FK de inventario a categorias.id, solo a categorias.nombre_categoria (Q-53).

§4. Antipatrón: doble convención de FK a usuarios
4.1 Diez columnas → usuarios.usuario (username)
#	Tabla	Columna
1	devoluciones_inventario	devuelto_por
2	devoluciones_inventario	recibido_por
3	instalaciones	id_usuario_asignado
4	inventario	modificado_por
5	movimientos_inventario	modificado_por
6	notificaciones	usuario_id
7	proyectos	modificado_por
8	salidas_inventario	entregado_a
9	salidas_inventario	entregado_por
10	ventas	vendedor
4.2 Cuatro columnas → usuarios.id (PK)
#	Tabla	Columna
1	certificado_historial	usuario_id
2	notificaciones_web	usuario_id
3	recordatorios	created_by
4	recordatorios	usuario_id
4.3 Lectura

Tablas viejas usan usuario (username). Tablas nuevas usan id (PK).
Migración a medias. Ver Q-62 para plan.
4.4 Antipatrón análogo con roles

Dos tablas referencian roles.rol por string en vez de roles.id:

    permiso_rol.rol

    usuarios.rol

Ver Q-63.
§5. Correcciones al documento v1.1
#	Sección v1.1	Corrección para v1.2
1	Estados de cotización	aceptada → aprobada. Agregar facturada.
2	movimientos_inventario	No era "rename". Era doble FK → consolidado a proyectos.
3	inventario.categoria	No es drift: tiene FK a categorias.nombre_categoria. Reformular §2.
4	inventario.precio	Existe (ver Q-55 sobre si es zombie).
5	Flujo Venta→Cotización→Proyecto	No es cadena directa. Venta origina el nombre; proyecto lo materializa; cotización cuelga del proyecto.
6	Almacenes	Solo "Bodega General" es real. Los 6 nombres documentados no aparecen (Q-56).
7	§14 "Drift detectado"	Incluir nota metodológica: auditoría inicial contra dump truncado, 3 falsos positivos.
8	Conteo de tablas que apuntan a proyectos	4 por FK + cotizaciones por convención sin FK.
9	Antipatrón FK a usuarios	No son 8 columnas: son 10 (faltaba ventas.vendedor y desdoblaba salidas_inventario).
10	Antipatrón FK a roles	No es 1 tabla: son 2 (permiso_rol, usuarios).
§6. Pendientes abiertos (Q-49 a Q-68)
ID	Pendiente	Estado
Q-49	¿Cómo conviven ventas, cotizaciones y proyectos? (reformulada)	Abierto
Q-49.e	ventas.vendedor FK a usuarios.usuario. Migrar a id.	Abierto
Q-49.f	ventas.cotizacion / levantamiento son mediumblob. ¿Migrar a storage?	Abierto
Q-49.g	ventas.ubicacion es point. ¿Conectar con geocerca_*?	Abierto
Q-49.h	ventas.estatus varchar sin FK a catálogo estatus (tipo='venta').	Abierto
Q-50	cotizaciones.estatus: BD dice aprobada, doc dice aceptada. ¿facturada entra al ciclo de inventario?	Abierto
Q-52	inventario.apartados / cantidad_apartados son varchar con lógica mixta. Rediseñar.	Abierto
Q-53	inventario.categoria FK existe pero apunta a categorias.nombre_categoria. ¿Migrar a categorias.id?	⏳ Requiere SHOW INDEX + COUNT
Q-54	Relación entre "4 familias" conceptuales y 33 categorías del catálogo.	Abierto
Q-55	inventario.precio existe pero el doc dice que no hay costo. ¿Zombie?	Abierto
Q-56	Almacenes reales ≠ 6 nombres documentados. Relevar.	⏳ Requiere relevamiento
Q-59	¿agent_conversations, solicitudes_ubicacion, certificado_historial, instalacion_fotos son features activas o residuos?	Abierto
Q-60	Telescope activo en producción.	✅ Confirmado activo (tabla poblada, en grafo)
Q-61	H9 del audit era falso. Correr grafo completo antes de cerrar.	✅ Cerrado (29/29)
Q-62	Doble convención FK a usuarios. Plan de migración a id.	✅ Recalculado: 10 vs 4
Q-63	roles.rol referenciado por string.	✅ Confirmado: 2 tablas (permiso_rol, usuarios)
Q-64	Documentar certificado_historial e instalacion_fotos.	Abierto
Q-65	¿cotizaciones.proyecto_id → proyectos.id existe?	✅ Resuelto: NO existe FK
Q-66	salidas_inventario.entregado_a ¿debe ser FK a usuarios o entidad externa?	Abierto (nuevo)
Q-67	movimientos_inventario.instalacion — columna mal nombrada tras consolidación.	Abierto (nuevo)
Q-68	Nullability asimétrica de nombre_proyecto entre las 4 tablas migradas.	Abierto (nuevo)
6.1 Consultas pendientes para cerrar Q-53 y Q-56
sql

-- Q-53: ¿categorias.nombre_categoria es UNIQUE?
SHOW INDEX FROM categorias WHERE Column_name='nombre_categoria';
SELECT COUNT(*) FROM categorias;

-- Q-56: relevamiento de almacenes
-- ⏳ Pendiente definir consulta (revisar tablas relacionadas con inventario/almacenes)

§7. Tablas nuevas no documentadas en v1.1
Tabla	FK(s)	Mencionada en
certificado_historial	recordatorios.id, usuarios.id	No (Q-64)
instalacion_fotos	instalaciones.id	No (Q-64)
notificaciones_web	recordatorios.id, usuarios.id	Parcial
telescope_entries_tags	telescope_entries.uuid	No (infraestructura)
§8. Lecciones metodológicas

    No confiar en dumps truncados. phpMyAdmin exporta tablas individualmente
    y a veces corta blobs grandes (ventas.cotizacion, ventas.levantamiento son
    mediumblob — causa probable del truncamiento inicial).

    Verificar en vivo con information_schema.KEY_COLUMN_USAGE antes de
    declarar "FK faltante".

    Distinguir tipos de deuda: clave natural inmutable ≠ antipatrón. La regla
    "no se renombra" convierte el string en identificador estable, no en deuda.

    Contar con COUNT(*) antes de paginar. Un LIMIT n OFFSET m puede ocultar
    filas si el total real es mayor al asumido.

§9. Próximos pasos

    Correr consultas de Q-53 (categorias) y Q-56 (almacenes).

    Rellenar placeholders §6.1.

    Decidir Q-65: ¿agregar FK cotizaciones.proyecto_id → proyectos.id
    antes de empezar Cotizaciones? (recomendado)

    Decidir Q-62: plan de migración de las 10 columnas string → usuarios.id.
    ¿Antes o después de Cotizaciones?

    Congelar v1.2 y recién entonces arrancar el módulo Cotizaciones con el modelo
    correcto.

Anexo A — Árbol de dependencias (texto)
text

ventas
  └── proyectos (nombre_proyecto, UNIQUE)
        ├── movimientos_inventario (fk_mov_proyecto, col: instalacion)
        ├── salidas_inventario (fk_salidas_proyecto)
        ├── devoluciones_inventario (fk_devoluciones_proyecto, nullable)
        ├── instalaciones (fk_instalaciones_proyecto)
        │     └── instalacion_fotos
        └── cotizaciones (proyecto_id — SIN FK, ver Q-65)

usuarios
  ├── (por usuario.username): 10 columnas — ver §4.1
  └── (por usuario.id): 4 columnas — ver §4.2

roles
  ├── permiso_rol.rol
  └── usuarios.rol

categorias
  └── inventario.categoria (por nombre_categoria)

estatus
  └── instalaciones.estatus_instalacion

recordatorios
  ├── certificado_historial
  └── notificaciones_web

telescope_entries
  └── telescope_entries_tags

Fin del borrador v1.2. Placeholders marcados con ⏳ se rellenan tras correr
las consultas de Q-53 y Q-56.
text


---

**Notas sobre el archivo:**

- Lo dejé como borrador autocontenido: podés guardarlo directo y ya sirve.
- Los únicos dos puntos sin cerrar son **Q-53** (categorias) y **Q-56** (almacenes), ambos no bloqueantes para Cotizaciones salvo el de categorias, que conviene resolverlo antes por higiene de claves.
- Agregué **Q-66, Q-67, Q-68** como nuevos pendientes surgidos de esta sesión.
- El **Anexo A** es el diagrama textual del árbol que pediste; si preferís un formato Mermaid o un diagrama más formal, lo cambio.

¿Querés que ajuste algo — extensión, orden de secciones, agregar el diagrama Mermaid, o separar los Q en un archivo aparte?