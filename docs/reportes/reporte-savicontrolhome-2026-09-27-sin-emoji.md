
# SAVI Control Home
## Reporte de Estado del Sistema

**Fecha:** 27 de septiembre de 2026
**Versión del reporte:** 1.0
**Responsable técnico:** savilinux-art
**Sistema en producción:** https://savilinux-server.tail50a173.ts.net

---

# SLIDE 1 — Portada

## SAVI Control Home
### Sistema Integral de Gestión Operativa

| | |
|---|---|
| **Estado global** | [OK] Operativo en producción |
| **Módulos activos** | 11 |
| **Última fase completada** | FASE K — Recordatorios y Certificados |
| **Stack** | Laravel 13 · PHP 8.4 · MariaDB 11.8 |
| **Fecha del reporte** | 27 de septiembre de 2026 |

---

# SLIDE 2 — Resumen Ejecutivo

## ¿Qué es SAVI Control Home?

Sistema de gestión integral que centraliza la operación de instalaciones, inventario, ventas, proyectos y clientes, con trazabilidad completa y notificaciones automáticas por Telegram y web.

## Estado Actual

[OK] **Operativo** — En producción, con usuarios reales usando el sistema.

## Logros del período

- **FASE K completada y deployada**: módulo completo de recordatorios y certificados con automatización vía Telegram
- **Sistema de notificaciones unificado**: un solo canal para todas las alertas del sistema
- **Infraestructura productiva estable**: WebSocket en tiempo real, bot de Telegram, scheduler automático
- **Documentación viva**: 12 revisiones de contexto técnico acumuladas

## Próximos pasos

1. **Fortalecimiento de seguridad** — Blindaje de rutas, rotación de secretos
2. **Tests automatizados** — Cobertura del 80% de flujos críticos
3. **FASE B — Normalización de base de datos** — Eliminar deuda técnica histórica

---

# SLIDE 3 — Estado por Módulo

| Módulo | Estado | Cobertura | Notas |
|---|---|---|---|
| Autenticación y Roles | [OK] Completo | 100% | Roles + permisos granulares |
| Dashboard | [OK] Completo | 100% | Vistas por rol |
| Inventario | [OK] Completo | 100% | Con categorías y estatus |
| Ventas | [OK] Completo | 100% | Con exportación |
| Cotizaciones | [OK] Completo | 100% | Con PDF y envío |
| Clientes | [OK] Completo | 100% | Con RFC y régimen fiscal |
| Proyectos | [OK] Completo | 100% | Con trazabilidad |
| Instalaciones | [OK] Completo | 100% | Con fotos, mapa, geocercas |
| Asignaciones | [OK] Completo | 100% | Con instaladores |
| Ubicaciones (Traccar) | [OK] Completo | 100% | Tiempo real vía Reverb |
| Notificaciones | [OK] Completo | 100% | Unificadas (web + Telegram) |
| **Recordatorios** | [OK] **FASE K** | 100% | Con scheduler automático |
| **Certificados** | [OK] **FASE K** | 100% | Con renovación y avisos |
| Bot de Telegram | [OK] Completo | 100% | Comandos + botones inline |
| Reportes | [OK] Completo | 90% | Falta dashboard de certs |

**Leyenda:** [OK] Completo · [MEDIA] En progreso · [PEND] Pendiente

---

# SLIDE 4 — Logros Recientes (FASE K)

## Módulo de Recordatorios y Certificados

**Duración:** 6 sesiones de trabajo (26-27 septiembre 2026)

### Construido

1. **CRUD web completo** de recordatorios con filtros y KPIs
2. **Scheduler automático** que procesa recordatorios cada minuto
3. **Sistema de certificados** con avisos escalonados (15/7/3/1/0 días)
4. **Renovación automatizada** desde Telegram con un solo toque
5. **Comandos de bot**: `/recordar`, `/recordatorios`, `/certificados`, `/nuevo_cert`, `/cancelar_recordatorio`
6. **Observer de Instalaciones** que genera avisos automáticos al admin
7. **Notificaciones web unificadas** en el dashboard

### Deployado en producción

- [OK] Código en `main` y en el servidor productivo
- [OK] 3 tablas migradas sin incidencias
- [OK] Scheduler corriendo cada minuto
- [OK] Webhook de Telegram apuntando a producción
- [OK] Zona horaria corregida y sincronizada

---

# SLIDE 5 — Arquitectura General


**Tiempo total desde programación hasta envío: 6 minutos, sin intervención humana.**

---

# SLIDE 11 — Seguridad implementada

## Actualmente activo

- [OK] **Autenticación con sesión** — Middleware `auth.session` en rutas protegidas
- [OK] **Autorización granular** — Sistema de permisos por rol (`Administrador`, `Ventas`, `Instalador`, `Contabilidad`, etc.)
- [OK] **Idempotencia en webhook Telegram** — Sin duplicados por `update_id`
- [OK] **CSRF protegido** — Laravel default + tokens en formularios
- [OK] **Validación de entrada** — Enums y reglas en cada controller
- [OK] **Backups previos a migraciones productivas** — Dump antes de cada deploy
- [OK] **HTTPS en producción** — Tailscale Funnel + Let's Encrypt
- [OK] **Aislamiento de entorno dev/prod** — Bases de datos separadas
- [OK] **Reverb WebSocket autenticado** — Con `REVERB_APP_SECRET`
- [OK] **Sesiones firmadas** — Cookies encriptadas por Laravel

## En proceso

- [PEND] Blindaje de rutas sin middleware (5 rutas identificadas)
- [PEND] Rotación de secretos expuestos (`REVERB_APP_SECRET`)

---

# SLIDE 12 — Seguridad pendiente (prioridad alta)

## Riesgos detectados

### [CRIT] Rutas sin middleware de autenticación

**Ubicación:** `routes/web.php`
**Rutas afectadas:**
- `POST /telegram/send-location/start/{id}`
- `POST /telegram/send-location/end/{id}`
- `GET /salidas/{id}/imprimir`
- `GET /test-telegram-system`
- `GET /test-notify/{id}`

**Impacto:** Cualquiera que conozca las URLs puede accederlas sin login.
**Solución:** Envolver en `Route::middleware(['auth.session'])` o eliminar las de prueba.
**Estimación:** 30 minutos.

### [CRIT] Secreto de Reverb expuesto

**Ubicación:** `REVERB_APP_SECRET` en `.env`
**Motivo:** Expuesto en un chat de desarrollo el 26 sep.
**Solución:** Rotar el secreto y reiniciar Reverb.
**Estimación:** 15 minutos.

### [MEDIA] Login default de Traccar

**Ubicación:** Servidor de rastreo GPS (puerto 8443)
**Sospecha:** Usuario `admin` / contraseña `admin` (default de fábrica).
**Solución:** Verificar y cambiar si aplica.
**Estimación:** 5 minutos.

---

# SLIDE 13 — Roadmap: Prioridad ALTA

**Objetivo:** Subir la seguridad y robustez inmediatas.

| ID | Tarea | Duración | Impacto |
|---|---|---|---|
| A1 | Tests del Procesador + ReagendarCertificado | 4-6 h | [CRIT] Crítico |
| A2 | Tests del InstalacionObserver | 2-3 h | [CRIT] Crítico |
| A3 | Tests del RecordatorioController | 2-3 h | [CRIT] Crítico |
| A4 | Blindar rutas sin middleware | 30 min | [CRIT] Crítico |
| A5 | Sincronizar timezone en laptop | 10 min | [ALTA] Alto |
| A6 | Rotar `REVERB_APP_SECRET` | 15 min | [ALTA] Alto |

**Total estimado:** 9-13 horas (2 sesiones de trabajo)

**Resultado esperado:** Robustez 6/10 → 9/10 · Seguridad 6/10 → 9/10

---

# SLIDE 14 — Roadmap: Prioridad MEDIA y BAJA

## Prioridad MEDIA (siguiente trimestre)

| ID | Tarea | Duración |
|---|---|---|
| B1 | Reintentos exponenciales + alertas | 4-6 h |
| B2 | CI/CD con GitHub Actions | 4-6 h |
| B3 | Mover pendientes-*.txt a docs/ versionado | 1-2 h |
| B4 | Observabilidad: métricas y health checks | 4-6 h |
| B5 | Tests de Feature: comandos del bot | 3-4 h |

## Prioridad BAJA (refinamientos)

| ID | Tarea | Duración |
|---|---|---|
| C1 | Limpiar jobs/failed_jobs en prod | 30 min |
| C2 | Revisar flujo "instalador completa desde bot" | 2-3 h |
| C3 | Certificados del SAT (P28) | Depende del negocio |
| C4 | Dashboard dedicado de certificados | 4-6 h |
| C5 | WhatsAppNotificador real | 4-8 h |

---

# SLIDE 15 — Lista de Pruebas (Índice)

## Organización por flujo funcional

Las pruebas se ordenan de acuerdo al recorrido natural del usuario y del sistema, de lo más básico a lo más complejo.

**Secciones:**

1. **Autenticación y sesión** — 6 pruebas
2. **Dashboard** — 4 pruebas
3. **Inventario** — 5 pruebas
4. **Ventas** — 5 pruebas
5. **Clientes** — 4 pruebas
6. **Proyectos** — 4 pruebas
7. **Instalaciones** — 7 pruebas
8. **Ubicaciones (Traccar)** — 4 pruebas
9. **Recordatorios (FASE K)** — 10 pruebas
10. **Certificados (FASE K)** — 8 pruebas
11. **Bot de Telegram** — 8 pruebas
12. **Observer de Instalaciones (FASE K)** — 5 pruebas
13. **Notificaciones Web (FASE K)** — 4 pruebas

**Leyenda de estados:**
- [OK] Verificado en producción
- [OK] Verificado en desarrollo (no prod)
- [PEND] Pendiente de verificar
- [FALLA] Falla conocida

---

# SLIDE 16 — Pruebas: Autenticación y Dashboard

## 1. Autenticación y sesión

| # | Prueba | Estado |
|---|---|---|
| 1.1 | Login con credenciales válidas redirige al dashboard | [OK] |
| 1.2 | Login con credenciales inválidas muestra error | [OK] |
| 1.3 | Sesión persiste después de navegar entre módulos | [OK] |
| 1.4 | Logout limpia la sesión y redirige al login | [OK] |
| 1.5 | Acceso a ruta protegida sin login redirige a login | [OK] |
| 1.6 | Permisos por rol se respetan (ej. Ventas no ve Admin) | [OK] |

## 2. Dashboard

| # | Prueba | Estado |
|---|---|---|
| 2.1 | Carga correctamente según rol del usuario | [OK] |
| 2.2 | KPIs se calculan con datos reales | [OK] |
| 2.3 | Acceso rápido lleva a las rutas correctas | [OK] |
| 2.4 | Widget de notificaciones muestra conteo correcto | [OK] |

---

# SLIDE 17 — Pruebas: Módulos operativos

## 3. Inventario

| # | Prueba | Estado |
|---|---|---|
| 3.1 | Listar productos con filtros y paginación | [OK] |
| 3.2 | Crear producto con validación | [OK] |
| 3.3 | Editar producto existente | [OK] |
| 3.4 | Exportar inventario a CSV/Excel | [OK] |
| 3.5 | Ajustar stock y registrar movimiento | [OK] |

## 4. Ventas

| # | Prueba | Estado |
|---|---|---|
| 4.1 | Listar ventas con filtros | [OK] |
| 4.2 | Crear venta con productos y cliente | [OK] |
| 4.3 | Cambiar estatus de venta | [OK] |
| 4.4 | Exportar ventas | [OK] |
| 4.5 | Generar PDF de venta | [OK] |

## 5. Clientes

| # | Prueba | Estado |
|---|---|---|
| 5.1 | CRUD de clientes con RFC y régimen fiscal | [OK] |
| 5.2 | Validación de RFC | [OK] |
| 5.3 | Asociar cliente a proyecto | [OK] |
| 5.4 | Búsqueda por RFC o razón social | [OK] |

## 6. Proyectos

| # | Prueba | Estado |
|---|---|---|
| 6.1 | CRUD de proyectos | [OK] |
| 6.2 | Asociar proyecto a venta | [OK] |
| 6.3 | Ver trazabilidad de materiales | [PEND] FASE C |
| 6.4 | Filtrar por estatus | [OK] |

---

# SLIDE 18 — Pruebas: Instalaciones y Ubicaciones

## 7. Instalaciones

| # | Prueba | Estado |
|---|---|---|
| 7.1 | CRUD completo con fotos | [OK] |
| 7.2 | Cambiar estatus (asignada → en_proceso → completada) | [OK] |
| 7.3 | Asignar instaladores | [OK] |
| 7.4 | Ver mapa con instalaciones | [OK] |
| 7.5 | Subir fotos de avance (inicio, proceso, fin) | [OK] |
| 7.6 | Notificación Telegram al asignar instalación | [OK] |
| 7.7 | Botón inline "Iniciar jornada" desde Telegram | [OK] |

## 8. Ubicaciones (Traccar)

| # | Prueba | Estado |
|---|---|---|
| 8.1 | Compartir ubicación desde Telegram (inicio) | [OK] |
| 8.2 | Compartir ubicación desde Telegram (fin) | [OK] |
| 8.3 | Mapa actualiza en tiempo real vía WebSocket | [OK] |
| 8.4 | Geocercas generan alertas al entrar/salir | [OK] |

---

# SLIDE 19 — Pruebas: Recordatorios (FASE K)

## 9. Recordatorios

| # | Prueba | Estado |
|---|---|---|
| 9.1 | Listar recordatorios con filtros (estatus/tipo/usuario/fecha) | [OK] |
| 9.2 | Crear recordatorio desde web (form completo) | [OK] |
| 9.3 | Crear recordatorio desde bot (`/recordar`) | [OK] |
| 9.4 | Editar recordatorio existente | [OK] |
| 9.5 | Cancelar recordatorio pendiente | [OK] |
| 9.6 | Eliminar recordatorio | [OK] |
| 9.7 | KPIs se calculan correctamente (hoy, 7 días, certs, total) | [OK] |
| 9.8 | Scheduler envía automáticamente sin intervención | [OK] |
| 9.9 | Envío por Telegram | [OK] |
| 9.10 | Envío por canal Web (NotificacionWeb) | [OK] |

**Evidencia crítica:**
Prueba 9.8 verificada el 27 sep a las 18:42:02.
Recordatorio creado por bot a las 18:36, enviado automáticamente por cron a las 18:42.

---

# SLIDE 20 — Pruebas: Certificados (FASE K)

## 10. Certificados

| # | Prueba | Estado |
|---|---|---|
| 10.1 | Crear certificado con avisos 15/7/3/1/0 | [OK] |
| 10.2 | Certificado vencido se marca como completado | [OK] |
| 10.3 | Re-agendado al siguiente aviso del array | [OK] |
| 10.4 | Botón inline "Renovar" desde Telegram | [OK] |
| 10.5 | Registro en `certificado_historial` | [OK] |
| 10.6 | Nuevo ciclo se crea automáticamente (+1 año) | [OK] |
| 10.7 | Emojis de urgencia según días restantes | [OK] |
| 10.8 | Link de renovación guardado y mostrado | [OK] |

**Ejemplo verificado:**
- Certificado #6 "Dominio Demo" vencía 02/10/2026
- Botón Renovar presionado
- Historial registrado: 02/10/2026 → 02/10/2027
- Nuevo ciclo #7 creado con vencimiento 02/10/2027

---

# SLIDE 21 — Pruebas: Bot de Telegram

## 11. Comandos del bot

| # | Prueba | Estado |
|---|---|---|
| 11.1 | `/help` muestra lista de comandos | [OK] |
| 11.2 | `/recordar <texto> \| <fecha>` crea recordatorio | [OK] |
| 11.3 | `/recordar` con formato inválido → mensaje de ayuda | [OK] |
| 11.4 | `/recordar` con fecha pasada → rechazo | [OK] |
| 11.5 | `/recordatorios` lista pendientes del usuario | [OK] |
| 11.6 | `/cancelar_recordatorio <id>` cancela pendiente | [OK] |
| 11.7 | `/certificados` lista certificados con días restantes | [OK] |
| 11.8 | `/nuevo_cert <nombre> \| <fecha>` registra certificado | [OK] |

## 12. Webhook e idempotencia

| # | Prueba | Estado |
|---|---|---|
| 12.1 | Webhook recibe updates y responde 200 OK | [OK] |
| 12.2 | Idempotencia: updates duplicados son ignorados | [OK] |
| 12.3 | Callback de botones inline funciona | [OK] |
| 12.4 | Ubicaciones compartidas se procesan correctamente | [OK] |

**Nota:** En el log de producción el tipo aparece como "otro" en vez de "command" (cosmético, no afecta funcionalidad).

---

# SLIDE 22 — Pruebas: Observer y Notificaciones Web

## 13. Observer de Instalaciones (FASE K)

| # | Prueba | Estado |
|---|---|---|
| 13.1 | Cambio a `completada` crea recordatorio por admin | [OK] |
| 13.2 | Cambio a `entrega` cierra previos + crea nuevo | [OK] |
| 13.3 | Cambio a `cancelada` cierra previos + crea nuevo | [OK] |
| 13.4 | No dispara si el estatus no cambió | [OK] |
| 13.5 | No dispara si no hay admins (log warning) | [OK] |

## 14. Notificaciones Web (FASE K)

| # | Prueba | Estado |
|---|---|---|
| 14.1 | Se crean registros en `notificaciones_web` al enviar | [OK] |
| 14.2 | Badge en navbar muestra conteo correcto | [OK] |
| 14.3 | Listado en `/notificaciones` carga correctamente | [OK] |
| 14.4 | Marcar como leída / todas como leídas funciona | [OK] |

---

# SLIDE 23 — Cómo mediremos el 10/10

## Dimensiones y pesos

| Dimensión | Peso | Estado actual | Meta |
|---|---|---|---|
| Robustez (errores, reintentos, tests) | 30% | 6/10 | 9/10 |
| Seguridad (rutas, secretos) | 25% | 6/10 | 9/10 |
| Testing (cobertura de flujos críticos) | 20% | 3/10 | 8/10 |
| Observabilidad (saber sin adivinar) | 10% | 4/10 | 8/10 |
| Mantenibilidad (CI/CD, documentación) | 10% | 7/10 | 9/10 |
| Documentación (contexto preservado) | 5% | 9/10 | 10/10 |

**Meta global:** 10/10 sostenible

## Plan por trimestre

**Trimestre 1:** A1 + A4 + A6 → ~9/10 en seguridad y robustez
**Trimestre 2:** A2 + A3 + B1 + B2 → ~9.5/10
**Trimestre 3:** B3 + B4 + B5 → 10/10 sostenible

## Filosofía

> "Un proyecto robusto que hace 8 cosas bien > uno frágil que hace 20."

---

# SLIDE 24 — Próximos pasos

## Inmediatos (esta semana)

1. [OK] Ejecutar A5 (sincronizar timezone laptop) — 10 min
2. [CRIT] Ejecutar A4 (blindar rutas sin middleware) — 30 min
3. [CRIT] Ejecutar A6 (rotar Reverb secret) — 15 min
4. [MEDIA] A1 (tests del Procesador) — 4-6 h

## Corto plazo (próximo mes)

- Tests del Observer y del Controller
- Reintentos exponenciales con backoff
- CI/CD con GitHub Actions
- Documentación versionada

## Mediano plazo (trimestre)

- FASE B — Normalización de base de datos
- Dashboard dedicado de certificados
- WhatsApp real (si se requiere)

---

# ANEXO A — Glosario

| Término | Definición |
|---|---|
| **FASE** | Bloque de trabajo temático (A-K) |
| **M0, M10** | Hitos técnicos dentro de FASE B (normalización) |
| **Recordatorio** | Entrada en `recordatorios` con fecha, canal y estatus |
| **Certificado** | Tipo especial de recordatorio con ciclo de renovación |
| **Observer** | Patrón Laravel que reacciona a cambios en modelos |
| **Morph** | Relación polimórfica (un modelo se asocia a varios tipos) |
| **Reagendado** | Mover `fecha_hora_programada` al siguiente aviso |
| **Webhook** | Endpoint que recibe eventos externos (Telegram) |
| **Scheduler** | Sistema de cron que ejecuta comandos periódicos |
| **Idempotencia** | Propiedad de no duplicar efectos ante repetición |

---

# ANEXO B — Comandos útiles

## Operación diaria

```bash
# Procesar recordatorios manualmente (si es necesario)
php artisan recordatorios:procesar

# Verificar el scheduler
php artisan schedule:list

# Ver logs en tiempo real
php artisan pail
tail -f storage/logs/laravel.log
