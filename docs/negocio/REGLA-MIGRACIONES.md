markdown

# REGLA DE ORO — MIGRACIONES DE BASE DE DATOS

> Documento nacido del incidente del 8-oct-2026 (Q-104/Q-105).
> ProyectoSAVI — Prohibido modificar el schema fuera de migraciones.

---

## 1. La regla

**Todo cambio de schema (ALTER, CREATE, DROP, ADD COLUMN, ADD INDEX, FK, etc.)
en CUALQUIER entorno va por migración.**

Sin excepciones. Aunque parezca trivial. Aunque estés apurado. Aunque sea
"solo en tu laptop para probar algo".

> Si no está en `database/migrations/`, no existe.

---

## 2. Por qué

El 8-oct-2026 se descubrió que:

- El schema de `testing` llevaba **desactualizado desde al menos el 7-oct**.
  Los 91 tests verdes corrían contra un schema viejo → no eran representativos.
- En `prod`, la columna `instalaciones.id_usuario_asignado` **no existía**,
  pero la migración asumía que sí. Resultado: `migrate --force` falló.
- En `prod` había una instalación huérfana (`casa de la paz`, id=37) que
  rompió la creación del FK `fk_instalaciones_proyecto` porque el
  nombre_proyecto no existía en `proyectos`.

**Todos estos problemas vienen del mismo antipatrón:**
`ALTER TABLE` directo en phpMyAdmin / tinker, sin migración.

### La promesa que se rompe

`php artisan migrate:fresh` debe reconstruir un schema **idéntico** al de
prod. Si hubo ALTERs manuales, esa promesa se rompe: el schema reconstruido
diverge silenciosamente del real. Nadie lo nota hasta que algo explota.

---

## 3. Cómo se hace (flujo correcto)

    Crear archivo de migración:
    php artisan make:migration nombre_descriptivo_de_lo_que_hace

    Editar el archivo en database/migrations/YYYY_MM_DD_HHMMSS_*.php

    Hacer la migración IDEMPOTENTE (ver sección 4)

    Probar en dev:
    php artisan migrate

    Probar en testing:
    DB_DATABASE=proyectosavi_testing php artisan migrate

    Correr la suite:
    php artisan test

    Commit:
    git add database/migrations/YYYY_MM_DD_HHMMSS_*.php
    git commit -m "feat(migrations): descripción corta"

    Push:
    git push origin main

    Deploy (prod):
    ssh savilinux@savilinux-server
    cd /var/www/proyectosavi
    mysqldump ... > /tmp/prod-backup-$(date +%F).sql # SIEMPRE
    git pull origin main
    php artisan migrate --force
    php artisan migrate:status | grep <nombre> # confirmar [Ran]

text


---

## 4. Cómo hacer una migración idempotente

Una migración **idempotente** se puede correr múltiples veces sin romper,
y detecta el estado actual antes de actuar. Es la diferencia entre una
migración que arregla y una que empeora.

### Antipatrón (NO hacer)

```php
public function up(): void
{
    // ❌ Asume que la columna existe
    DB::statement("ALTER TABLE instalaciones MODIFY id_usuario_asignado VARCHAR(255) NULL");
}

Si la columna no existe (como en prod), esto explota.
Patrón correcto
php

public function up(): void
{
    // ✅ Detecta el estado antes de actuar
    if (!$this->columnExists('instalaciones', 'id_usuario_asignado')) {
        echo "  [skip] columna no existe, nada que hacer.\n";
        return;
    }

    if ($this->isNullable('instalaciones', 'id_usuario_asignado')) {
        echo "  [skip] ya es nullable.\n";
        return;
    }

    DB::statement("ALTER TABLE instalaciones MODIFY id_usuario_asignado VARCHAR(255) NULL");
    echo "  [modify] ahora es nullable.\n";
}

private function columnExists(string $table, string $col): bool
{
    return (bool) DB::selectOne("
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = ?
          AND COLUMN_NAME = ?
    ", [$table, $col]);
}

private function isNullable(string $table, string $col): bool
{
    $row = DB::selectOne("
        SELECT IS_NULLABLE FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = ?
          AND COLUMN_NAME = ?
    ", [$table, $col]);

    return $row && $row->IS_NULLABLE === 'YES';
}

Regla de oro de la idempotencia

Antes de tocar nada, preguntarse:

    ¿Qué pasa si esto ya está aplicado? → [skip]

    ¿Qué pasa si el objeto no existe? → [skip] o crear

    ¿Qué pasa si falla a mitad? → ¿queda un estado peor?

Si la respuesta a la última es "sí, queda peor", la migración no está lista.
5. Si ya hiciste un ALTER manual (residuos históricos)

No hay que entrar en pánico ni revertir. Se hace así:
1. Detectar el residuo
sql

SHOW CREATE TABLE <tabla>;
-- o
SHOW COLUMNS FROM <tabla>;

Y comparar con lo que dicen las migraciones en database/migrations/.
Si algo en la BD no está en migraciones → es drift.
2. Crear migración que lo versione

Si el ALTER manual ya está aplicado en algún entorno, la migración debe:

    Detectar si el cambio ya está hecho → [skip]

    Aplicarlo si no está → [modify] o [add]

    Nunca romper si el estado es ambiguo → lanzar excepción clara

3. Verificar en los 3 entornos
bash

# DEV
php artisan migrate
php artisan migrate:status

# TESTING
DB_DATABASE=proyectosavi_testing php artisan migrate
DB_DATABASE=proyectosavi_testing php artisan migrate:status

# PROD (server)
git pull origin main
mysqldump ... > /tmp/prod-backup-$(date +%F).sql   # backup primero
php artisan migrate --force

Cada entorno debe imprimir [skip] o [modify] según su estado. Todas
deben terminar [Ran].
6. Backup antes de migrar en prod

Regla: nunca correr migrate --force en prod sin backup.
bash

# En el server, ANTES de migrate
mysqldump -u root -p proyectosavi \
  instalaciones proyectos ventas migrations \
  > /tmp/prod-backup-$(date +%F-%H%M).sql

ls -lh /tmp/prod-backup-*.sql   # verificar que se creó

Y bajarlo a la laptop:
bash

scp savilinux@savilinux-server:/tmp/prod-backup-*.sql ~/backups/

7. Cómo chequear drift (auditoría periódica)

Una vez al mes (o cuando algo se sienta raro), correr:
bash

# Dump DDL de dev
mysqldump -u root -p --no-data --skip-comments proyectosavi > /tmp/dev.sql

# Dump DDL de testing
mysqldump -u root -p --no-data --skip-comments proyectosavi_testing > /tmp/testing.sql

# Diff normalizado (ignorar AUTO_INCREMENT)
diff <(sed 's/AUTO_INCREMENT=[0-9]*//' /tmp/dev.sql) \
     <(sed 's/AUTO_INCREMENT=[0-9]*//' /tmp/testing.sql) \
  > /tmp/drift-dev-vs-testing.diff

wc -l /tmp/drift-dev-vs-testing.diff
head -100 /tmp/drift-dev-vs-testing.diff

Interpretación:

    0 líneas → todo alineado ✅

    Pocas líneas con AUTO_INCREMENT o ENGINE → cosmético, ignorar

    Diferencias en columnas, FKs, índices → drift funcional, requiere migración

Lo mismo se puede hacer dev↔prod y testing↔prod, pero prod requiere
conectarse al server o traer un dump.
8. Antipatrones prohibidos
Acción	Por qué está mal
ALTER TABLE en phpMyAdmin directo	Rompe la promesa de migrate:fresh
ALTER TABLE en tinker directo	Igual
Editar migración que ya corrió en prod	Cambia la historia — mejor crear una nueva
migrate:fresh en prod	Borra TODOS los datos
migrate --force sin backup	Sin red de seguridad si algo sale mal
Asumir que la BD tiene una columna sin verificar	Falla con SQLSTATE[42S22] como hoy
Ignorar [Pending] en migrate:status	Ese [Pending] va a explotar cuando alguien corra migrate
9. El día que algo falle

Si una migración falla en prod:

    STOP. No reintentes el migrate sin entender.

    Verificar el estado actual con SHOW COLUMNS / SHOW CREATE TABLE.

    Verificar si la migración está en [Pending] o [Ran] (en migrations).

    Si la tabla quedó a medio tocar, ver qué se alcanzó a hacer.

    Consultar a alguien si no estás seguro — mejor preguntar que romper.

    Nunca dropear la fila de migrations sin entender qué implica.

    Backup antes de cualquier fix, aunque creas que "es chico".

10. Resumen de una línea

    Sin migración no hay cambio. Sin backup no hay deploy.

Referencias

    Q-104: Auditar y cerrar drift dev↔testing

    Q-105: Regla — prohibido ALTER manual (este documento)

    v28 del traspaso: pendientes-proyectosavi-2026-10-08-v28.txt

    Caso real: "casa de la paz" (instalación #37 huérfana, 8-oct-2026)

    Caso real: id_usuario_asignado (columna inexistente en prod, 8-oct-2026)

text


---

## Cómo guardarlo

```bash
cd /home/savilinux/laravel/proyectosavi

# Crear el archivo
nano docs/negocio/REGLA-MIGRACIONES.md
# (pegar el contenido de arriba, guardar con Ctrl+O, Enter, Ctrl+X)

# Verificar
ls -la docs/negocio/REGLA-MIGRACIONES.md
cat docs/negocio/REGLA-MIGRACIONES.md | head -5

# Commit
git add docs/negocio/REGLA-MIGRACIONES.md
git commit -m "docs: regla de oro de migraciones (Q-105, post incidente 8-oct)"
git push origin main

Por qué vale la pena tenerlo escrito

Dentro de 3 meses, cuando estés cansado un viernes a las 22:00 y alguien te diga "che, cambiame rápido una columnita en prod", vas a leer este doc y vas a decir:

    "No. Va por migración."

Y eso te va a ahorrar el próximo incidente. Es la clase de documento que te salva de vos mismo.

Cuando lo tengas guardado, avisame y seguimos con el cierre de prod (patch de migración 190000 + pull + migrate).