markdown

# CAMBIOS v19 → v20 — ProyectoSAVI

**Fecha:** 2 oct 2026
**Base:** `pendientes-proyectosavi-2026-09-30-v19.txt`
**Destino:** `pendientes-proyectosavi-2026-10-02-v20.txt`

Resumen de una línea: se completó la **trazabilidad de materiales**
end-to-end y se implementó **soft-delete de usuarios** con guard de
sesión y blindaje `forceDelete`. Todo queda en dev, deploy diferido
al 5 oct por congelamiento.

---

## 1. Header

| Campo | v19 | v20 |
|---|---|---|
| Fecha | 30 sep 2026 | 2 oct 2026 |
| Revisión | 19 | 20 |
| Subtítulo | "DOCS/NEGOCIO V1 + CUESTIONARIO RESUELTO (44)..." | "TRAZABILIDAD COMPLETA + SOFT-DELETE USUARIOS + REDISEÑO movimientos_inventario + FIX NOTIFICACION + BACKFILL + VISTA TRAZABILIDAD VERIFICADA EN NAVEGADOR" |

---

## 2. Sección auth — 3 cambios

**§1 Resumen 30s** — añadido párrafo:
> AuthSessionMiddleware AHORA revalida contra DB en cada request para
> detectar usuarios soft-deleted. Un usuario borrado pierde acceso sin
> esperar a que cierre sesión manualmente.

**§2 Flujo login** — añadida nota:
> Con SoftDeletes activo, un usuario soft-deleted simplemente NO se
> encuentra → login falla con "credenciales incorrectas". No hay que
> tocar AuthController.

**§3 Middleware** — `auth.session` marcado `[ACTUALIZADO 02 oct]`:
- Antes: solo verificaba `Session::has('user_usuario')`
- Ahora: además hace `Usuario::where('usuario',...)->first()`
  - Si null → `Session::flush()` + redirect login "Tu sesión expiró"
- Añadida nota: 1 query por request, cachear con Cache::remember si crece.

**§7 Mensajes** — añadido:
> Sesión viva pero usuario soft-deleted → "Tu sesión expiró"

---

## 3. Sección NUEVA §5 — Reglas de soft-delete

Contenido:
- Decisión: usuarios NUNCA se borran físicamente
- Implementación: `deleted_at` + trait + override `forceDelete`
- Reglas de oro (3)
- Identificadores de un solo uso (usuario, correo, telegram_chat_id,
  traccar_device_id) NO se liberan al soft-deletear
- Motivo: 9 FKs RESTRICT + 3 FKs CASCADE identificadas
- Archivos involucrados
- Estado: aplicado en dev, pendiente prod

---

## 4. Sección NUEVA — Trazabilidad de materiales

Insertada antes de "Estado del entorno". Cubre:
- Modelo de negocio (Cliente → Proyecto → Instalaciones)
- Reglas: entradas/ajustes sin proyecto, salidas/devoluciones con proyecto
- Estructura completa de `movimientos_inventario` con índices
- Flujo de escritura vía observers (SalidaDetalle, DevolucionDetalle)
- Archivos: observers, service, command backfill
- Vista `proyectos/show.blade.php` + `ProyectoController@show`
- Nota: NO usar observer para stock (controladores ya lo hacen)
- Estado: ✅ funcional, verificado en navegador

---

## 5. §8 Errores conocidos — 2 nuevos

**ERROR 11** (nuevo):
> Correr migración soft-delete EN PROD sin haber testeado en dev con
> un usuario con FKs.

**ERROR 12** (nuevo):
> Aplicar `use SoftDeletes` en el modelo ANTES de correr la migración
> que crea `deleted_at`. MariaDB tira ERROR 1054 en cada query.
> ORDEN CORRECTO: migración primero, luego trait.

---

## 6. §9 Tests — añadido TEST D

TEST D — Usuario soft-deleted:

    Login como usuario X en pestaña A

    En otra sesión (admin), soft-deletear X desde /usuarios

    Refrescar pestaña A → debe redirigir a /login

    Restaurar X (UPDATE usuarios SET deleted_at = NULL WHERE ...)

    Login como X → debe funcionar

text


---

## 7. ★ Estado actual — reescrito

**Logros añadidos (01 oct):**
- Migración `remove_nombre_proyecto_from_clientes_table`
- Migración `add_cliente_id_to_proyectos_table`
- Migración `redesign_movimientos_inventario_table`
- Migración `make_proyecto_id_nullable_in_movimientos_inventario_table`
- Fix bug crítico `Notificacion.php` (clase duplicada)

**Logros añadidos (02 oct):**
- Trazabilidad end-to-end verificada en navegador
- Soft-delete de usuarios implementado y testeado en dev
- AuthSessionMiddleware revalida DB
- Auditoría completa de FKs hacia usuarios (14 FKs)

**Pendientes críticos** — añadidos:
- Commit de observadores + backfill + fix Notificacion
- Deploy a prod: soft-delete + trazabilidad + migraciones 01-02 oct

---

## 8. §4 Estado del entorno — 4 cambios

**Laptop:**
- PHP `8.4.25` → **`8.3.33` (dev)**
- Añadido: `Soft-delete: ✅ APLICADO (migración 2026_10_02_112731)`
- Git: `<pendiente verificar>` → `main (pendiente push tras levantar congelamiento)`

**Server:**
- Añadido: `Soft-delete: ⏳ PENDIENTE (diferido hasta levantar congelamiento)`
- Añadido: `Trazabilidad: ⏳ PENDIENTE (diferido hasta levantar congelamiento)`

---

## 9. §5 Estado de fases

**FASE C — Trazabilidad materiales:**
- Antes: `⏳`
- Ahora: `✅ COMPLETADA EN DEV / ⏳ PENDIENTE DEPLOY PROD`

Resto sin cambios.

---

## 10. §10 Pendientes operativos — reorganizado

**POST-DEPLOY INMEDIATO (5 oct)** — expandido:
- Push de commits locales
- Deploy a prod (git pull + migrate --force + optimize:clear)
- Migración telegram_chat_id en server
- Migración add_soft_deletes_to_usuarios_table en prod
- Migraciones 01 oct
- Smoke test prod

**COMMIT PENDIENTE HOY** — nueva sección:
- Commit A: soft-delete (3 archivos)
- Commit B: trazabilidad (7 archivos)

**LIMPIEZA DE .bak** — nueva sección:
- 3 archivos `.bak` sueltos a mover a `/tmp/`

**CONFIG** — nueva sección:
- Crear `config/psysh.php` con `['pager' => null]`

**PENDIENTES DE DISEÑO** — nueva sección:
- §7.1 SalidaInventario::proyecto() apunta mal
- §7.2 Migrar nombre_proyecto varchar → proyecto_id bigint
- §7.3 Migrar modificado_por varchar → user_id bigint (CONGELADO)
- §7.4 Limpiar apartados/cantidad_apartados

**PENDIENTES DEL CUESTIONARIO** — Q-10 marcado RESUELTO.

---

## 11. §7 Notas técnicas — expandido

**Sección auth** — añadida nota de revalidación DB.

**Sección nueva: SOFT-DELETE** — 6 puntos:
- 9 FKs RESTRICT → delete() físico falla con 1451
- 3 FKs CASCADE → forceDelete() es bomba latente
- Los 4 UNIQUEs NO se liberan (decisión intencional)
- IDs 3 y 7 ausentes por borrado intencional pre-trazabilidad
- ORDEN: migración primero, luego trait

**Sección nueva: TRAZABILIDAD** — 4 puntos:
- Observer en el DETALLE, no en el padre
- `#[ObservedBy]` más limpio que boot() en Laravel 11+
- Controladores YA actualizan existencia, no añadir observer de stock
- Backfill idempotente vía whereNotExists

**Sección nueva: .BAK SUELTOS** — los .bak son peligrosos, limpiar.

**Sección nueva: MYSQL/MARIADB** — restore con glob produce
"redireccionamiento ambiguo", usar `LATEST=$(ls -t *.sql | head -1)`.

**Sección nueva: TIMEZONE** — verificar config/app.php. Si es UTC,
timestamps en DB están en UTC (cosmético).

**Sección DEPLOY** — añadido: en prod no hacer tests destructivos.

---

## 12. 🚫 Congelamiento — actualizado

Añadido al final:
> El congelamiento SIGUE ACTIVO. Todos los cambios de esta sesión
> quedan en laptop. El día del deploy se hace todo junto:
>   1. Push de commits locales
>   2. Pull en server
>   3. Backup de BD prod
>   4. php artisan migrate --force (todas las pendientes)
>   5. optimize:clear
>   6. Smoke test prod

---

## 13. §14 Próxima acción — reescrito

**PASO 0** — añadido `mysql -e "SELECT id, usuario, deleted_at FROM usuarios;"`

**PASO 1** — CERRAR COMMITS DE HOY (urgente):
- Commit A: soft-delete
- Commit B: trazabilidad

**PASO 2** — 5 OCT 2026 (levantar congelamiento)

**PASO 3** — ACTUALIZAR A v21

---

## 14. §15 Archivos clave — reescrito

**EN GIT** — añadidos 12 archivos nuevos/modificados:
- app/Http/Middleware/AuthSessionMiddleware.php (ACTUALIZADO)
- app/Models/Usuario.php (ACTUALIZADO)
- database/migrations/2026_10_02_112731_add_soft_deletes_*.php
- app/Observers/SalidaDetalleObserver.php
- app/Observers/DevolucionDetalleObserver.php
- app/Services/TrazabilidadService.php
- app/Console/Commands/BackfillTrazabilidad.php
- app/Models/Notificacion.php
- app/Models/SalidaDetalle.php
- app/Models/DevolucionDetalle.php
- resources/views/proyectos/show.blade.php
- app/Http/Controllers/ProyectoController.php

**EN DISCO PERO NO EN GIT** — nueva sección de basura:
- 3 archivos `.bak` a limpiar

**BACKUPS GENERADOS ESTA SESIÓN** — nueva sección:
- 3 dumps de usuarios (10:56, 11:28, 11:33)
- 2 dumps completos (01 y 02 oct)

---

## 15. Secciones SIN cambios

- §4 Cómo proteger una ruta nueva
- §6 Cómo obtener el usuario actual (solo añadida nota soft-delete)
- §13 Reglas de oro (nuevas van a §5 nueva)
- §11 Referencia rápida
- §12 Archivos clave relacionados con auth
- §3.1-3.6 (pendientes C, I, M, L, calificación, cuestionario)
- §8 Hallazgos de schema M0
- §9 Bloques restantes
- §11 Preguntas clave estado

---

## Métricas del diff

| Categoría | Cantidad |
|---|---|
| Secciones nuevas | 2 (§5 soft-delete + trazabilidad) |
| Secciones actualizadas | 10 |
| Secciones sin cambios | 12 |
| Errores nuevos | 2 (E11, E12) |
| Tests nuevos | 1 (TEST D) |
| Logros añadidos | 9 (5 de 01 oct + 4 de 02 oct) |
| Pendientes añadidos | 6 bloques |
| Líneas netas | +~180 (v19 ~1300 → v20 ~1480) |

---

## Acción sugerida

1. Guardar v20 en `pendientes-proyectosavi-2026-10-02-v20.txt`
2. Revisar este diff antes de commitear
3. Commit:

git add pendientes-proyectosavi-2026-10-02-v20.txt
git add CAMBIOS-v19-a-v20.md
git commit -m "docs: actualizar pendientes v19 → v20 (soft-delete + trazabilidad)"
text

4. Push diferido al 5 oct junto con el resto del deploy

---

**FIN DEL DIFF**

Cómo usarlo

Guardar los 2 archivos:
bash

cd ~/laravel/proyectosavi

# v20 — pégame el contenido del mensaje anterior y guárdalo
nano pendientes-proyectosavi-2026-10-02-v20.txt

# diff — pégame el contenido de este mensaje
nano CAMBIOS-v19-a-v20.md

Verificación rápida:
bash

wc -l pendientes-proyectosavi-2026-10-02-v20.txt CAMBIOS-v19-a-v20.md
# esperado: v20 ~1480, CAMBIOS ~330

# Comparar con v19 si aún lo tienes
diff <(grep -c "" pendientes-proyectosavi-2026-09-30-v19.txt) \
     <(grep -c "" pendientes-proyectosavi-2026-10-02-v20.txt)

Si quieres el diff real generado por git (no el resumen que hice):
bash

# Solo si ambos están trackeados en git
git diff pendientes-proyectosavi-2026-09-30-v19.txt \
         pendientes-proyectosavi-2026-10-02-v20.txt > CAMBIOS-v19-a-v20.diff

