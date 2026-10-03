markdown

# INVENTARIO Y TRAZABILIDAD — proyectosavi

**Versión:** 1.2 (borrador)
**Fecha:** 01-oct-2026
**Base:** v1.1 + auditoría de schema en vivo (sesiones 30-sep y 01-oct 2026)
**Estado:** Grafo de FKs cerrado. Pendiente sincronización dev = prod.

---

## Infraestructura de acceso (sesión 01-oct-2026)

### Entornos

| Entorno | Ubicación | MariaDB | PHP | Estado |
|---|---|---|---|---|
| **Dev** | Laptop local | 11.8.9 |8.4.25| Schema sí, datos **no** |
| **Prod** | `savilinux-server` (Tailscale) | 11.8.6 | 8.4.25 | Schema + datos poblados (~3 MB total) |

### Accesos configurados

- **SSH a prod:** `savilinux@savilinux-server` (clave SSH cargada, sin password).
- **phpMyAdmin prod:** `http://savilinux-server.tail50a173.ts.net:8081`.
- **Credenciales MySQL:** `~/.my.cnf` en **ambas máquinas** (laptop y server), con `chmod 600`. Permite correr `mysql` y `mysqldump` sin `-u`/`-p`.

```ini
# ~/.my.cnf (ambas máquinas)
[client]
user=root
password=<password_base32>


## §0. Nota metodológica (leer antes que nada)

### Falsos positivos del audit original

La auditoría inicial que originó esta revisión se hizo contra un **dump truncado**
exportado desde phpMyAdmin. El dump cortó blobs grandes (`ventas.cotizacion` y
`ventas.levantamiento` son `mediumblob` — causa probable), lo que produjo **al menos
3 hallazgos críticos falsos**:

| Hallazgo del audit original | Realidad verificada |
|---|---|
| H1: `ventas` no existe | ❌ Falso. Existe, con FK a `usuarios.usuario` y UNIQUE en `nombre_proyecto`. |
| H9: `instalaciones.estatus_instalacion` sin FK | ❌ Falso. Tiene FK a `estatus.estatus`. |
| H4: `inventario.categoria` sin FK | ❌ Falso. Tiene FK a `categorias.nombre_categoria`. |
| H3/H10: referencias por string a proyecto | 🟡 Matizado. Son FK reales, usan `nombre_proyecto` como clave natural (no `id`). |

### Drift dev vs prod

El segundo audit (01-oct) detectó que **dev y prod divergen**:

| Aspecto | Dev (laptop) | Prod (server) |
|---|---|---|
| `inventario.apea` | `NOT NULL` | `DEFAULT NULL` |
| `inventario` filas | 0 (vacía) | ~1973 |
| MariaDB | 11.8.9 | 11.8.6 |
| PHP | 8.3.33 | 8.4.25 |

**Regla operativa:** verificar siempre contra **prod**. Dev se sincroniza
periódicamente con `scripts/pull-prod.sh` (§10).

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
3. **Convención operativa:** lo que se mueve, entrega, devuelve, instala y cotiza
   apunta a `proyectos` por nombre.
4. **Excepción:** `cotizaciones.proyecto_id → proyectos.id` (PK numérico).
   Motivación: cotización es documento formal.
   ⚠️ **Deuda:** esta FK **no existe como constraint** (Q-65).

---

## §2. Cambios aplicados a la BD (sesión 30-sep/01-oct)

### 2.1 `movimientos_inventario` — consolidación de FK

- Detectada **doble FK**: a `proyectos` y a `ventas`. Redundante y sobre-restringida.
- **Dropeada:** `movimientos_inventario_instalacion_foreign` (→ ventas).
- **Conservada:** `fk_mov_proyecto` → `proyectos.nombre_proyecto`.
- Índice huérfano verificado: ninguno.
- ⚠️ **Residuo:** la columna sigue llamándose `instalacion` (Q-67).

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

    cotizaciones NO aparece. No tiene ninguna FK (Q-65).

    ventas es raíz: solo proyectos.nombre_proyecto → ventas.nombre_proyecto
    la referencia. Nadie la apunta por PK.

    Telescope activo con schema poblado (Q-60).

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
Migración a medias. Ver Q-62.
4.4 Antipatrón análogo con roles

Dos tablas referencian roles.rol por string en vez de roles.id:

    permiso_rol.rol

    usuarios.rol

Ver Q-63.
§5. Correcciones al documento v1.1
#	Sección v1.1	Corrección para v1.2
1	Estados de cotización	aceptada → aprobada. facturada no entra (módulo futuro).
2	movimientos_inventario	No era "rename". Era doble FK → consolidado a proyectos.
3	inventario.categoria	No es drift: tiene FK a categorias.nombre_categoria.
4	inventario.precio	Existe con datos. Reformular: no es "zombie", es legacy a evaluar (Q-55).
5	Flujo Venta→Cotización→Proyecto	No es cadena directa. Venta origina el nombre; proyecto lo materializa; cotización cuelga del proyecto.
6	Almacenes	"6 reales" REFUTADO por datos. Solo 'Bodega General' en prod (Q-56).
7	§14 "Drift detectado"	Incluir nota metodológica: auditoría inicial contra dump truncado, 3 falsos positivos.
8	Conteo de tablas que apuntan a proyectos	4 por FK + cotizaciones por convención sin FK.
9	Antipatrón FK a usuarios	No son 8 columnas: son 10.
10	Antipatrón FK a roles	No es 1 tabla: son 2 (permiso_rol, usuarios).
11	Conteo de tablas	No son ~25: son 42 (§7).
12	§"APEA"	Documentar: local por almacén, formato A{anaquel}S{sección}L{nivel}.
13	Categorías	Están en migración activa (§6.3).
§6. Estado de los catálogos
6.1 categorias (~30 valores técnicos + 4 familias conceptuales)

Valores observados en prod (parcial): PLACAS DECORATIVAS, DOMOTICA,
ACCESORIO, ILUMINACION, VENTILACION, PUNTO DE ACCESO, SWITCH,
RED E INTERNET, VIDEO, ALARMA, CABLEADO, AUDIO, TV,
RACK O GABINETE, SOPORTE O BASE, NO BREAK, CONECTOR O CLAVIJA,
INTERFON O VIDEOPORTERO, TELEFONIA, CERRADURA, FIBRA OPTICA,
AIRE ACONDICIONADO, CABLES (Accesorios), CONTROL REMOTO, HARDWARE,
MICROFONO, CARGADOR, CORTINA, PATCHCORD, INSUMOS Y HERRAMIENTAS,
LUTRON, CCTV, Poes, Accesorios Racks, Multicontactos y Regletas, ...

Cuatro familias conceptuales (cuestionario A2):

    Material de instalaciones

    Equipos electrónicos

    Consumibles de instalaciones

    Herramientas de trabajo

Son agrupaciones de las ~30 categorías técnicas. Ver Q-54.
6.2 almacen (⚠️ reabierto)

El 100% de las filas de inventario en prod tienen almacen = 'Bodega General'.

Los 6 nombres mencionados en el cuestionario (lutron, cctcv, sonos,
bocinas, racks, vivero) no aparecen en ningún registro.

Q-56 reabierto. Posibilidades: plan futuro, confusión con áreas dentro de
Bodega General, o entidad externa. Requiere aclaración del usuario.
6.3 Categorización en migración activa

Registros modificados en los últimos días muestran recategorización en curso:
Antes	Después
ACCESORIO	Accesorios Racks
ILUMINACION (genérico)	LUTRON (marca)
VIDEO	CCTV

El catálogo categorias está siendo reescrito. La lista de "33 categorías"
es un blanco móvil. El v1.2 debe anotar la transición.
§7. Inventario de tablas (42 confirmadas)

Listado completo verificado en prod (information_schema.TABLES):
text

certificado_historial, cotizacion_detalles, cotizaciones,
devolucion_detalle, devoluciones_inventario, failed_jobs,
geocerca_alertas, instalacion_fotos, instalacion_instalador,
instalaciones, inventario, movimientos_inventario,
notificaciones, notificaciones_web, permisos, permiso_rol,
personal_access_tokens, proyectos, recordatorios, roles,
salida_detalle, salidas_inventario, sessions, solicitudes_ubicacion,
telescope_entries, telescope_entries_tags, ubicaciones_usuarios,
usuarios, ventas, ...

Tablas relevantes para el modelo de datos (no documentadas en v1.1):
Tabla	Relevancia	Q
salida_detalle	Líneas de salida — ¿vs salidas_inventario.productos JSON?	Q-81
devolucion_detalle	Líneas de devolución	—
cotizacion_detalles	Líneas de cotización	—
instalacion_instalador	Tabla pivote N:M	Q-82
ubicaciones_usuarios	¿Tracking GPS de técnicos?	Q-83
geocerca_alertas	Conecta con ventas.ubicacion	Q-83

Modelo real = cabecera + detalle. Las columnas JSON (salidas_inventario.productos)
conviven con tablas de detalle (salida_detalle). Fuente de verdad por definir.
§8. Cambios de schema en prod (verificados en dump 01-oct)
8.1 inventario (prod)
text

existencia          int(11) NOT NULL DEFAULT 0
modelo              varchar(255) NULL
descripcion         varchar(255) NOT NULL
marca               varchar(255) NULL                  ← confirmado Q-75
categoria           varchar(255) NOT NULL              ← FK a categorias
almacen             varchar(255) NOT NULL              ← string libre, sin catálogo
apea                varchar(255) DEFAULT NULL          ← nullable en prod
imagen              mediumblob NULL                    ← causa probable truncamiento
imagen_url          varchar(255) NULL
fecha_modificacion  datetime NOT NULL
comentarios         varchar(255) NULL
apartados           varchar(255) NULL                  ← log disfrazado de campo
cantidad_apartados  varchar(255) NULL                  ← log disfrazado de campo
modificado_por      varchar(255) NOT NULL
created_at          timestamp NULL
updated_at          timestamp NULL
codigo_origen       varchar(255) NULL  UNIQUE          ← ID del sistema B legacy
fecha_alta          datetime NULL
precio              decimal(12,2) NULL                 ← con datos (Q-55)

8.2 Ejemplo de apartados (log de texto libre)
text

"PACIFICA-1 | APARTADO:PROYECTO | FOLIO:PACIFICA-1 | VENCE:2026-07-02
SE APARTA 1pza. PARA PROYECTO PACIFICA-1"

Formato inconsistente (\r\n vs \n). Confirma Q-52: es un log, no un campo numérico.
8.3 salidas_inventario (prod)
text

id, nombre_proyecto, entregado_por, entregado_a,
productos longtext CHECK(json_valid),
fecha_hora_salida, observaciones, created_at, updated_at

FKs: fk_salidas_proyecto, entregado_por_foreign, entregado_a_foreign.
§9. Pendientes abiertos (Q-49 a Q-83)
ID	Pendiente	Estado
Q-49	¿Cómo conviven ventas, cotizaciones y proyectos?	Abierto
Q-49.e	ventas.vendedor FK a usuarios.usuario. Migrar a id.	Abierto
Q-49.f	ventas.cotizacion / levantamiento mediumblob. ¿Migrar a storage?	Abierto
Q-49.g	ventas.ubicacion point. ¿Conectar con geocerca_*?	Abierto
Q-49.h	ventas.estatus varchar sin FK a catálogo.	Abierto
Q-50	cotizaciones.estatus: aprobada vs aceptada. ¿facturada?	✅ Cerrado: aprobada, facturada fuera
Q-52	inventario.apartados / cantidad_apartados varchar con lógica mixta.	✅ Confirmado: rediseñar
Q-53	inventario.categoria FK a categorias.nombre_categoria. ¿Migrar a id?	⏳ Requiere SHOW INDEX + COUNT
Q-54	"4 familias" vs ~30 categorías del catálogo.	🟡 Reencuadrado: 2 capas
Q-55	inventario.precio existe con datos. ¿Legacy o activo?	✅ Reencuadrado: decidir
Q-56	Almacenes reales ≠ "Bodega General".	🔴 Reabierto por datos
Q-59	¿agent_conversations, solicitudes_ubicacion, certificado_historial, instalacion_fotos activas o residuos?	Abierto
Q-60	Telescope activo en producción.	✅ Confirmado activo
Q-61	H9 del audit era falso.	✅ Cerrado (29/29)
Q-62	Doble convención FK a usuarios. Plan migración a id.	✅ Recalculado: 10 vs 4
Q-63	roles.rol referenciado por string.	✅ Confirmado: 2 tablas
Q-64	Documentar certificado_historial e instalacion_fotos.	Abierto
Q-65	¿cotizaciones.proyecto_id → proyectos.id existe?	✅ Resuelto: NO existe FK
Q-66	salidas_inventario.entregado_a ¿FK a usuarios o entidad externa?	✅ Cerrado: FK a usuarios
Q-67	movimientos_inventario.instalacion — columna mal nombrada.	✅ Cerrado: renombrar a nombre_proyecto
Q-68	Nullability asimétrica de nombre_proyecto en las 4 tablas migradas.	Abierto
Q-81	salida_detalle vs salidas_inventario.productos (JSON) — fuente de verdad	🆕 Abierto
Q-82	instalacion_instalador N:M — ¿reemplaza instalaciones.id_usuario_asignado?	🆕 Abierto
Q-83	ubicaciones_usuarios + geocerca_alertas — ¿tracking?	🆕 Abierto
Q-40	inventario.apea nullable	✅ Cerrado: nullable en prod, alinear dev
Q-75	inventario.marca / modelo	✅ Cerrado: existen y están poblados
9.1 Consultas pendientes
sql

-- Q-53: ¿categorias.nombre_categoria es UNIQUE?
SHOW INDEX FROM categorias WHERE Column_name='nombre_categoria';
SELECT COUNT(*) FROM categorias;

-- Listado completo de las 42 tablas (con table_rows)
SELECT table_name, table_rows
FROM information_schema.TABLES
WHERE table_schema='proyectosavi'
ORDER BY table_name;

§10. Sincronización dev ↔ prod

Como solo hay un desarrollador, sin ventana de coordinación.
10.1 Acceso
Entorno	Acceso
Prod	SSH: savilinux@savilinux-server. MySQL root con ~/.my.cnf.
Dev	localhost:3306. MySQL root con ~/.my.cnf.
phpMyAdmin prod	http://savilinux-server.tail50a173.ts.net:8081
10.2 Script scripts/pull-prod.sh
bash

#!/bin/bash
set -e

SERVER="savilinux@savilinux-server"
DB="proyectosavi"
TMP="/tmp/prod_$DB.sql"
BACKUP_DIR="$HOME/backups"

mkdir -p "$BACKUP_DIR"

echo "→ Backup dev..."
mysqldump "$DB" > "$BACKUP_DIR/dev_$(date +%F_%H%M).sql"

echo "→ Export prod vía SSH..."
ssh "$SERVER" \
  "mysqldump --single-transaction --routines --triggers --events \
   --ignore-table=$DB.telescope_entries \
   --ignore-table=$DB.telescope_entries_tags \
   $DB" > "$TMP"

echo "→ Reset dev..."
mysql -e "DROP DATABASE IF EXISTS $DB; CREATE DATABASE $DB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

echo "→ Import a dev..."
mysql "$DB" < "$TMP"

echo "✅ Dev = Prod ($(date))"

chmod +x scripts/pull-prod.sh. Requiere ~/.my.cnf en ambas máquinas (ya hecho).

Regla: *.sql en .gitignore.
10.3 Verificación post-pull
sql

SELECT COUNT(*) FROM information_schema.TABLES
  WHERE table_schema='proyectosavi';                               -- 42
SELECT COUNT(*) FROM inventario;                                   -- ~1973
SELECT IS_NULLABLE FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA='proyectosavi'
    AND TABLE_NAME='inventario' AND COLUMN_NAME='apea';            -- YES
SELECT COUNT(*) FROM information_schema.KEY_COLUMN_USAGE
  WHERE TABLE_SCHEMA='proyectosavi'
    AND REFERENCED_TABLE_NAME IS NOT NULL;                         -- 29

§11. Lecciones metodológicas

    No confiar en dumps truncados. mediumblob puede cortar exports.

    Verificar en vivo con information_schema antes de declarar FK faltante.

    Distinguir tipos de deuda: clave natural inmutable ≠ antipatrón.

    Contar con COUNT(*) antes de paginar.

    Sincronizar dev = prod antes de auditar. Dev con schema divergente
    produce conclusiones inválidas.

§12. Próximos pasos

    ~~Sincronizar dev = prod.~~ (script §10.2)

    Correr consultas pendientes: Q-53, listado completo de 42 tablas.

    Resolver Q-56 (almacenes — contradicción con datos).

    Decidir Q-65: agregar FK cotizaciones.proyecto_id → proyectos.id
    antes de empezar Cotizaciones.

    Decidir Q-62: plan de migración de las 10 columnas string → usuarios.id.

    Investigar Q-81, Q-82, Q-83 (tablas nuevas del modelo cabecera/detalle).

    Congelar v1.2 y arrancar módulo Cotizaciones.

Anexo A — Árbol de dependencias
text

ventas
  └── proyectos (nombre_proyecto, UNIQUE)
        ├── movimientos_inventario (fk_mov_proyecto, col: instalacion)
        ├── salidas_inventario (fk_salidas_proyecto)
        │     └── salida_detalle (?)
        ├── devoluciones_inventario (fk_devoluciones_proyecto, nullable)
        │     └── devolucion_detalle (?)
        ├── instalaciones (fk_instalaciones_proyecto)
        │     ├── instalacion_fotos
        │     └── instalacion_instalador (?)
        └── cotizaciones (proyecto_id — SIN FK, ver Q-65)
              └── cotizacion_detalles

usuarios
  ├── (por usuario.username): 10 columnas — §4.1
  └── (por usuario.id): 4 columnas — §4.2

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

ubicaciones_usuarios
geocerca_alertas       ← ¿tracking de técnicos?
solicitudes_ubicacion

Fin del borrador v1.2. Placeholders marcados con ⏳ o 🆕 se rellenan tras
correr las consultas pendientes y resolver Q-56.
text


