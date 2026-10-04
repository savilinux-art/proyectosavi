DOCUMENTO DE TRASPASO — Recuperación BD + Bloque 2.2 Trazabilidad

Fecha: 01-oct-2026
Estado: Recuperación esencial ✅ — faltan 3 tablas con drift de schema
Siguiente acción: diagnóstico de drift + fix quirúrgico + Bloque 2.2
0. CÓMO USAR ESTE DOCUMENTO

Pegar COMPLETO en chat nuevo + decir:

    "Estoy retomando ProyectoSAVI. Acabo de recuperar la BD dev desde un backup del 15-sep tras un migrate:fresh accidental. Recuperé usuarios/roles/ventas/proyectos/cotizaciones/categorias/estatus/clientes/permisos. Faltan inventario, instalaciones, salidas_inventario, geocercas por drift de schema. También tengo pendiente Bloque 2.2 de Trazabilidad (stubs + 1 test fallando). Adjunto contexto completo."

El asistente debe:

    Leer todo este documento.

    Confirmar que entendió estado + decisiones + pendientes.

    Pedir el output del diagnóstico de drift (sección 4) antes de escribir código.

    Esperar outputs antes de proponer cambios.

1. CONTEXTO DEL PROYECTO

ProyectoSAVI = sistema de gestión de instalaciones tecnológicas (CCTV, redes, domótica, audiovisuales).

Stack:

    Laravel 13.25.0, PHP 8.4.25, MariaDB 11.8.9 (dev)

    Blade + Bootstrap 5 + jQuery + DataTables + Leaflet

    Tests: Pest v4.7.8 con BD aislada proyectosavi_testing

    Repo: https://github.com/savilinux-art/proyectosavi

Reglas de oro:

    Auth CUSTOM: Session::put('user_id'/'user_usuario'/'user_rol'). Nunca Auth::user(). Middleware correcto: auth.session. El alias auth NO existe.

    Congelamiento activo hasta 5-oct-2026: NO deploy, NO migrate en server. Commits locales.

    Ediciones multi-línea de archivos grandes: script Python con match exacto + count()==1 + abort ANTES de write_text(). Nunca editar prod directo.

    Backup antes de tocar routes/web.php o bootstrap/app.php.

    Después de cambios: php artisan optimize:clear.

    Rutas/middleware: laptop → commit → push → pull en server.

Documentación viva:

    docs/negocio/MODELO-DATOS.md

    docs/negocio/FLUJOS.md

    docs/negocio/GLOSARIO.md

    docs/negocio/INVENTARIO-Y-TRAZABILIDAD.md (v1.2 borrador)

2. ⚠️ PROBLEMA RECIÉN RESUELTO: BD dev vaciada

Qué pasó: alguien corrió migrate:fresh (o equivalente) contra la BD dev proyectosavi. Todas las 44 tablas quedaron con schema intacto pero sin filas. Se perdió el usuario admin default.

Causa raíz NO confirmada al 100% pero descartado que sean los tests (phpunit.xml apunta a proyectosavi_testing, BD aislada).

Backups disponibles:

    ./backup_20260915_1513.sql (650 KB, 33 CREATE TABLE, 22 INSERT) ← usado

    ./backup_webhook_ok_20260915.sql (656 KB, sin inspeccionar)

Binlog: OFF → no hay recuperación punto-en-el-tiempo.
3. ESTADO ACTUAL DE LA RECUPERACIÓN
✅ Recuperado (11 tablas, con mysql --force)
Tabla	Filas
roles	6
usuarios	7 (incluye admin, id=1, rol='Administrador')
ventas	9
proyectos	7
cotizaciones	8
cotizacion_detalles	12
clientes	1
categorias	33
estatus	13
permisos	41
permiso_rol	(cargado, sin conteo explícito)
solicitudes_ubicacion	(cargado, sin conteo explícito)
ubicaciones_usuarios	(cargado, sin conteo explícito)
geocerca_estados	(cargado, sin conteo explícito)
salida_detalle	2 filas huérfanas (sin padre en salidas_inventario)

El admin volvió con su hash bcrypt original → misma contraseña de antes.
❌ Falló el import en (4 tablas)
Tabla	Filas	Error exacto
inventario	0	ERROR 1048 (23000) at line 97: Column 'apea' cannot be null
instalaciones	0	ERROR 1136 (21S01) at line 90: Column count doesn't match value count at row 1
salidas_inventario	0	ERROR 1136 (21S01) at line 2222: Column count doesn't match value count at row 1
geocercas	?	ERROR 1265 (01000) at line 2227: Data truncated for column 'longitud' at row 1

Causa común: drift de schema entre sep-15 y hoy.
Archivos temporales vivos en /tmp (útiles para retomar)
Archivo	Contenido
/tmp/restore_datos.sql	22 INSERT extraídos del backup, 2256 líneas, sin ESC
/tmp/restore_final.sql	Idem pero sin migrations ni sessions (20 INSERT)
/tmp/restore_clean.sql	Wrapper con SET FOREIGN_KEY_CHECKS=0 (limpio)
/tmp/tablas_backup.txt	Lista de 22 tablas que el backup puebla
/tmp/tablas_actual.txt	Lista de 44 tablas actuales
/tmp/import_force_errors.txt	Los 4 errores listados arriba
/tmp/proyectosavi_schema_actual.sql	Schema completo al momento del intento

Si el próximo chat es en la misma sesión de terminal, /tmp está vivo. Si reinicia, hay que regenerarlos desde backup_20260915_1513.sql.
4. SIGUIENTE ACCIÓN INMEDIATA — Diagnóstico de drift

Ejecutar y pegar el output:
bash

for t in inventario instalaciones salidas_inventario geocercas; do
  echo "═══════════ $t"
  echo "── backup (columnas por orden) ──"
  awk "/^CREATE TABLE \`$t\`/,/;/" backup_20260915_1513.sql \
    | grep -E "^  \`" | awk '{print NR": "$1}' | tr -d '`'

  echo "── actual (columnas por orden) ──"
  mysql -u root -pis660222 -N -e "SHOW COLUMNS FROM $t" proyectosavi 2>/dev/null \
    | awk '{print NR": "$1}'

  echo ""
done

Con ese output el próximo asistente puede entregar los fixes quirúrgicos tabla por tabla.

Credenciales MySQL dev: root / is660222 (cambiar cuando se pueda, ya estuvo expuesta).
5. BLOQUE 2.2 PENDIENTE — Trazabilidad
5.1 Contexto

Módulo Trazabilidad de Materiales = responder por proyecto: qué se cotizó, qué se entregó, qué se devolvió, diferencia, responsables, evidencias digitales, auditoría de discrepancias.

Ya existe: sección básica en resources/views/proyectos/show.blade.php con tabla Modelo/Marca/Descripción/Vendido/Entregado/Devuelto/Neto/Faltante.

Falta: enriquecer con badges de estado, discrepancias auditables, evidencias fotográficas, responsables por salida, reportes adicionales.
5.2 Decisiones cerradas (NO repreguntar)

    Q-81: salida_detalle manda, NO el JSON productos.

    Q-100: tabla evidencias_entrega — creada.

    Q-101: storage en storage/app/evidencias/{proyecto_id}/.

    Q-102: tabla trazabilidad_discrepancias — creada.

    Q-103: motor único parametrizado (TrazabilidadService).

    Q-104: salida_detalle.inventario_id NOT NULL.

    Q-105: Dompdf disponible.

    Q-106: discrepancias resueltas → visibles con badge (auditoría histórica). La diff viva se calcula siempre.

    Q-107: "resolver" = SOLO AUDITA (opción B). Implica corregido_por_salida_id y corregido_por_cotizacion_id.

    Q-108: subido_por / resuelto_por = varchar(255), FK lógica a usuarios.usuario.

    Q-110: índices en salida_detalle fuera del MVP (Fase 1.5).

    Q-111: huérfanos en salida_detalle → INNER JOIN natural (ignorar).

    Q-112: TrazabilidadService ya se inyecta en ProyectoController::show(). Cambios puro aditivos, no renombrar ni cambiar firmas.

5.3 Estado Bloque 2.2

Servicio app/Services/TrazabilidadService.php:

    ✅ paraProyecto() + 4 helpers (vendidoPorProyecto, entregadoPorProyecto, devueltoPorProyecto, merge) → implementados y usados

    ✅ trazabilidadCompleta() → enriquecido (lee discrepancias + evidencias por salida)

    ✅ responsablesPorProyecto() → query real con LEFT JOIN a evidencias

    ✅ registrarResolucion() → persistencia real, marca como 'resuelta'

    ❌ Los 4 wrappers (porArticulo, porCliente, porResponsable, porPeriodo) → Fase 4, sin tocar

    ❌ exportarPdfProyecto() → Fase 5, sin tocar

Archivos creados en Bloque 2.2:

    database/migrations/2026_10_01_000001_create_evidencias_entrega_table.php ✅ migrada

    database/migrations/2026_10_01_000002_create_trazabilidad_discrepancias_table.php ✅ migrada (había quedado vacía, se regeneró)

    app/Models/EvidenciaEntrega.php ✅

    app/Models/TrazabilidadDiscrepancia.php ✅

    tests/Feature/TrazabilidadServiceTest.php ✅ (5 tests)

Tests: ProyectoControllerTest 10/10 verdes. TrazabilidadServiceTest 4/5 verdes. Falla solo:
text

test_responsablesPorProyecto_devuelve_salidas_con_evidencias
Failed asserting that two strings are identical.
-'juan'
+'mwiza'  (varía entre corridas: 'herminia64', etc.)

Diagnóstico en curso del fallo: se descartó observer/mutator/$fillable. La causa tiene que estar en colisión de nombre_proyecto o default del schema. Pendiente: correr el dump diagnóstico (sección 7) después de cerrar la recuperación de BD.
5.4 Schema clave confirmado

salida_detalle: id, salida_id (bigint, no FK declarada), inventario_id (bigint NOT NULL), cantidad, precio_unitario, observaciones, timestamps. NO tiene descripcion.

salidas_inventario: id, nombre_proyecto, entregado_por, entregado_a, productos (longtext legacy, NO fuente de verdad), fecha_hora_salida, observaciones, timestamps. FK en testing → ventas.nombre_proyecto; en dev → proyectos.nombre_proyecto (Q-116 pendiente de reconciliar).

cotizacion_detalles: id, cotizacion_id, inventario_id (nullable = servicio/mano de obra), descripcion (snapshot), cantidad, precio_unitario, importe, timestamps.

evidencias_entrega (nueva): id, salida_id, proyecto_id, archivo_path, archivo_nombre_original, archivo_mime, archivo_tamano, notas, subido_por, timestamps. Índices en salida_id y proyecto_id.

trazabilidad_discrepancias (nueva): id, proyecto_id, inventario_id (nullable), tipo enum('faltante','sobrante'), cantidad_discrepancia, estado enum('abierta','resuelta'), detectado_en, resuelto_en, resuelto_por, notas_resolucion, corregido_por_salida_id, corregido_por_cotizacion_id, timestamps. 3 índices.

Casts ya agregados (Bloque 2.1):

    SalidaInventario::$casts ['fecha_hora_salida' => 'datetime']

    DevolucionInventario::$casts ['fecha_hora_devolucion' => 'datetime']

6. ESTADO GIT / COMMITS PENDIENTES

HEAD: be90110 (feat(proyectos): ajustes integración con cotizaciones)

Commits pendientes:

    Bloque 2.1 (fixes de tests): app/Models/SalidaInventario.php, app/Models/DevolucionInventario.php, tests/Feature/ProyectoControllerTest.php

        Mensaje propuesto: fix(tests): casts datetime + forceCreate productos + FK p-aislado-b (Q-45)

    Fase 1 Trazabilidad (tablas + modelos)

    Bloque 2.2 (servicio + tests)

Congelamiento hasta 5-oct-2026: commits locales, sin push, sin deploy.

Backups .bak-2026-09-30-* en el repo (ignorados por git):

    app/Http/Controllers/ProyectoController.php.bak-20260930-1611/1614

    resources/views/proyectos/show.blade.php.bak-20260930-1611/1614

7. PENDIENTES DE VERIFICACIÓN (para retomar)
7.1 Diagnóstico de drift (sección 4) — PRIORIDAD 1
7.2 Después de recuperar las 3 tablas, dump diagnóstico del test fallido:

Editar test_responsablesPorProyecto_devuelve_salidas_con_evidencias en tests/Feature/TrazabilidadServiceTest.php y agregar temporalmente:
php

$salida = trazSalida($proyecto, [['inventario_id' => $inv->id, 'cantidad' => 1]], 'juan', 'cliente-x');

dump([
    'nombre_proyecto'        => $proyecto->nombre_proyecto,
    'salida_en_memoria'      => $salida->toArray(),
    'salida_en_bd'           => DB::table('salidas_inventario')->where('id', $salida->id)->first(),
    'salidas_con_ese_nombre' => DB::table('salidas_inventario')->where('nombre_proyecto', $proyecto->nombre_proyecto)->pluck('entregado_por', 'id'),
    'usuarios_existentes'    => DB::table('usuarios')->pluck('usuario'),
]);

Y correr:
bash

php artisan test --filter=test_responsablesPorProyecto_devuelve_salidas_con_evidencias

7.3 Verificar los factories (por si hace falta)
bash

cat -n database/factories/ProyectoFactory.php database/factories/VentaFactory.php
php artisan app:table-structure salidas_inventario

8. LECCIONES APRENDIDAS — trampas encontradas
8.1 ESC sequences contaminan archivos SQL

La terminal (GNOME Terminal / VS Code con shell integration) inyecta secuencias OSC 633 (\033]633;...) cuando se generan archivos con echo/bloques {}. Solución: usar printf o filtrar con perl -pe 's/[\x00-\x08\x0B\x0C\x0E-\x1F]//g'.
8.2 Migración vacía por paste roto

Al pegar una migración en el editor, el archivo puede quedar en 0 líneas. Solución: wc -l antes de php artisan migrate.
8.3 mysql con --force continúa tras errores

Sin --force, el primer error aborta todo el archivo. Solución: usar mysql --force para importar bloque por bloque ignorando fallos puntuales.
8.4 FK violada silenciosamente

SET FOREIGN_KEY_CHECKS=0 es por sesión. Si hay mysql < archivo, todas las sentencias van en una conexión. Funciona. El error 1452 apareció cuando se ejecutó SOLO el bloque de usuarios sin ese SET previo.
8.5 grep "^INSERT INTO" no captura multi-row

Los INSERT INTO tabla VALUES (fila1),(fila2),... ocupan varias líneas. Solución: awk '/^INSERT INTO/{flag=1} flag{print} /;$/{flag=0}'.
8.6 Divergencia testing vs dev en FKs

    salidas_inventario.nombre_proyecto → ventas.nombre_proyecto (testing) vs proyectos.nombre_proyecto (dev). Q-116.

    proyectos.nombre_proyecto → unique en dev (proyectos_nombre_unique), FK en backup.

    ventas.vendedor → tenía ON DELETE CASCADE en backup, actual no.

9. ESTADO DEL CONGELAMIENTO

🚫 Congelado hasta 5-oct-2026.

    NO deploy al server.

    NO migrate en server.

    Commits quedan locales en laptop.

    Push al levantar el congelamiento.

Producción intacta — el incidente fue solo en dev local.
10. ORDEN DE PRÓXIMOS PASOS

    Diagnóstico drift (sección 4) → obtener output.

    Fix quirúrgico de inventario, instalaciones, salidas_inventario, geocercas (el próximo asistente provee código basado en el output del paso 1).

    Limpieza: borrar las 2 filas huérfanas en salida_detalle.

    Blindaje contra migrate:fresh accidental:

        Comando custom app:db-backup (envuelve mysqldump)

        Guard en AppServiceProvider que aborte migrate:fresh si APP_ENV=local y DB_DATABASE=proyectosavi sin --force

        Cron diario de backup con rotación 7 días

    Cerrar Bloque 2.2: fix del test fallido + commitear los 3 grupos de cambios pendientes.

    Después: Bloque 2.3 (vista) → 2.4 (controladores + rutas) → Fase 3 (dashboard) → 4 (vistas auxiliares) → 5 (PDF).

11. COMANDOS ÚTILES PARA RETOMAR
bash

# Estado del repo
git status
git log --oneline -5

# Estado de la BD dev
mysql -u root -pis660222 -e "SELECT 'usuarios' t, COUNT(*) n FROM usuarios UNION ALL SELECT 'roles', COUNT(*) FROM roles UNION ALL SELECT 'ventas', COUNT(*) FROM ventas UNION ALL SELECT 'proyectos', COUNT(*) FROM proyectos" proyectosavi

# Correr tests
php artisan test --filter=ProyectoControllerTest
php artisan test --filter=TrazabilidadServiceTest
php artisan test

# Verificar tablas nuevas
php artisan app:table-structure evidencias_entrega trazabilidad_discrepancias

# Limpiar cachés
php artisan optimize:clear

