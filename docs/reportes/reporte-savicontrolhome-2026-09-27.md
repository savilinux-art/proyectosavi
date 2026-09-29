
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
| **Estado global** | ✅ Operativo en producción |
| **Módulos activos** | 11 |
| **Última fase completada** | FASE K — Recordatorios y Certificados |
| **Stack** | Laravel 13 · PHP 8.4 · MariaDB 11.8 |
| **Fecha del reporte** | 27 de septiembre de 2026 |

---

# SLIDE 2 — Resumen Ejecutivo

## ¿Qué es SAVI Control Home?

Sistema de gestión integral que centraliza la operación de instalaciones, inventario, ventas, proyectos y clientes, con trazabilidad completa y notificaciones automáticas por Telegram y web.

## Estado Actual

🟢 **Operativo** — En producción, con usuarios reales usando el sistema.

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
| Autenticación y Roles | ✅ Completo | 100% | Roles + permisos granulares |
| Dashboard | ✅ Completo | 100% | Vistas por rol |
| Inventario | ✅ Completo | 100% | Con categorías y estatus |
| Ventas | ✅ Completo | 100% | Con exportación |
| Cotizaciones | ✅ Completo | 100% | Con PDF y envío |
| Clientes | ✅ Completo | 100% | Con RFC y régimen fiscal |
| Proyectos | ✅ Completo | 100% | Con trazabilidad |
| Instalaciones | ✅ Completo | 100% | Con fotos, mapa, geocercas |
| Asignaciones | ✅ Completo | 100% | Con instaladores |
| Ubicaciones (Traccar) | ✅ Completo | 100% | Tiempo real vía Reverb |
| Notificaciones | ✅ Completo | 100% | Unificadas (web + Telegram) |
| **Recordatorios** | ✅ **FASE K** | 100% | Con scheduler automático |
| **Certificados** | ✅ **FASE K** | 100% | Con renovación y avisos |
| Bot de Telegram | ✅ Completo | 100% | Comandos + botones inline |
| Reportes | ✅ Completo | 90% | Falta dashboard de certs |

**Leyenda:** ✅ Completo · 🟡 En progreso · ⏳ Pendiente

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

- ✅ Código en `main` y en el servidor productivo
- ✅ 3 tablas migradas sin incidencias
- ✅ Scheduler corriendo cada minuto
- ✅ Webhook de Telegram apuntando a producción
- ✅ Zona horaria corregida y sincronizada

---

# SLIDE 5 — Arquitectura General


**Tiempo total desde programación hasta envío: 6 minutos, sin intervención humana.**

---

# SLIDE 11 — Seguridad implementada

## Actualmente activo

- ✅ **Autenticación con sesión** — Middleware `auth.session` en rutas protegidas
- ✅ **Autorización granular** — Sistema de permisos por rol (`Administrador`, `Ventas`, `Instalador`, `Contabilidad`, etc.)
- ✅ **Idempotencia en webhook Telegram** — Sin duplicados por `update_id`
- ✅ **CSRF protegido** — Laravel default + tokens en formularios
- ✅ **Validación de entrada** — Enums y reglas en cada controller
- ✅ **Backups previos a migraciones productivas** — Dump antes de cada deploy
- ✅ **HTTPS en producción** — Tailscale Funnel + Let's Encrypt
- ✅ **Aislamiento de entorno dev/prod** — Bases de datos separadas
- ✅ **Reverb WebSocket autenticado** — Con `REVERB_APP_SECRET`
- ✅ **Sesiones firmadas** — Cookies encriptadas por Laravel

## En proceso

- ⏳ Blindaje de rutas sin middleware (5 rutas identificadas)
- ⏳ Rotación de secretos expuestos (`REVERB_APP_SECRET`)

---

# SLIDE 12 — Seguridad pendiente (prioridad alta)

## Riesgos detectados

### 🔴 Rutas sin middleware de autenticación

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

### 🔴 Secreto de Reverb expuesto

**Ubicación:** `REVERB_APP_SECRET` en `.env`
**Motivo:** Expuesto en un chat de desarrollo el 26 sep.
**Solución:** Rotar el secreto y reiniciar Reverb.
**Estimación:** 15 minutos.

### 🟡 Login default de Traccar

**Ubicación:** Servidor de rastreo GPS (puerto 8443)
**Sospecha:** Usuario `admin` / contraseña `admin` (default de fábrica).
**Solución:** Verificar y cambiar si aplica.
**Estimación:** 5 minutos.

---

# SLIDE 13 — Roadmap: Prioridad ALTA

**Objetivo:** Subir la seguridad y robustez inmediatas.

| ID | Tarea | Duración | Impacto |
|---|---|---|---|
| A1 | Tests del Procesador + ReagendarCertificado | 4-6 h | 🔴 Crítico |
| A2 | Tests del InstalacionObserver | 2-3 h | 🔴 Crítico |
| A3 | Tests del RecordatorioController | 2-3 h | 🔴 Crítico |
| A4 | Blindar rutas sin middleware | 30 min | 🔴 Crítico |
| A5 | Sincronizar timezone en laptop | 10 min | 🟠 Alto |
| A6 | Rotar `REVERB_APP_SECRET` | 15 min | 🟠 Alto |

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
- ✅ Verificado en producción
- 🟢 Verificado en desarrollo (no prod)
- ⏳ Pendiente de verificar
- ❌ Falla conocida

---

# SLIDE 16 — Pruebas: Autenticación y Dashboard

## 1. Autenticación y sesión

| # | Prueba | Estado |
|---|---|---|
| 1.1 | Login con credenciales válidas redirige al dashboard | ✅ |
| 1.2 | Login con credenciales inválidas muestra error | ✅ |
| 1.3 | Sesión persiste después de navegar entre módulos | ✅ |
| 1.4 | Logout limpia la sesión y redirige al login | ✅ |
| 1.5 | Acceso a ruta protegida sin login redirige a login | ✅ |
| 1.6 | Permisos por rol se respetan (ej. Ventas no ve Admin) | ✅ |

## 2. Dashboard

| # | Prueba | Estado |
|---|---|---|
| 2.1 | Carga correctamente según rol del usuario | ✅ |
| 2.2 | KPIs se calculan con datos reales | ✅ |
| 2.3 | Acceso rápido lleva a las rutas correctas | ✅ |
| 2.4 | Widget de notificaciones muestra conteo correcto | ✅ |

---

# SLIDE 17 — Pruebas: Módulos operativos

## 3. Inventario

| # | Prueba | Estado |
|---|---|---|
| 3.1 | Listar productos con filtros y paginación | ✅ |
| 3.2 | Crear producto con validación | ✅ |
| 3.3 | Editar producto existente | ✅ |
| 3.4 | Exportar inventario a CSV/Excel | ✅ |
| 3.5 | Ajustar stock y registrar movimiento | ✅ |

## 4. Ventas

| # | Prueba | Estado |
|---|---|---|
| 4.1 | Listar ventas con filtros | ✅ |
| 4.2 | Crear venta con productos y cliente | ✅ |
| 4.3 | Cambiar estatus de venta | ✅ |
| 4.4 | Exportar ventas | ✅ |
| 4.5 | Generar PDF de venta | ✅ |

## 5. Clientes

| # | Prueba | Estado |
|---|---|---|
| 5.1 | CRUD de clientes con RFC y régimen fiscal | ✅ |
| 5.2 | Validación de RFC | ✅ |
| 5.3 | Asociar cliente a proyecto | ✅ |
| 5.4 | Búsqueda por RFC o razón social | ✅ |

## 6. Proyectos

| # | Prueba | Estado |
|---|---|---|
| 6.1 | CRUD de proyectos | ✅ |
| 6.2 | Asociar proyecto a venta | ✅ |
| 6.3 | Ver trazabilidad de materiales | ⏳ FASE C |
| 6.4 | Filtrar por estatus | ✅ |

---

# SLIDE 18 — Pruebas: Instalaciones y Ubicaciones

## 7. Instalaciones

| # | Prueba | Estado |
|---|---|---|
| 7.1 | CRUD completo con fotos | ✅ |
| 7.2 | Cambiar estatus (asignada → en_proceso → completada) | ✅ |
| 7.3 | Asignar instaladores | ✅ |
| 7.4 | Ver mapa con instalaciones | ✅ |
| 7.5 | Subir fotos de avance (inicio, proceso, fin) | ✅ |
| 7.6 | Notificación Telegram al asignar instalación | ✅ |
| 7.7 | Botón inline "Iniciar jornada" desde Telegram | ✅ |

## 8. Ubicaciones (Traccar)

| # | Prueba | Estado |
|---|---|---|
| 8.1 | Compartir ubicación desde Telegram (inicio) | ✅ |
| 8.2 | Compartir ubicación desde Telegram (fin) | ✅ |
| 8.3 | Mapa actualiza en tiempo real vía WebSocket | ✅ |
| 8.4 | Geocercas generan alertas al entrar/salir | ✅ |

---

# SLIDE 19 — Pruebas: Recordatorios (FASE K)

## 9. Recordatorios

| # | Prueba | Estado |
|---|---|---|
| 9.1 | Listar recordatorios con filtros (estatus/tipo/usuario/fecha) | ✅ |
| 9.2 | Crear recordatorio desde web (form completo) | ✅ |
| 9.3 | Crear recordatorio desde bot (`/recordar`) | ✅ |
| 9.4 | Editar recordatorio existente | ✅ |
| 9.5 | Cancelar recordatorio pendiente | ✅ |
| 9.6 | Eliminar recordatorio | ✅ |
| 9.7 | KPIs se calculan correctamente (hoy, 7 días, certs, total) | ✅ |
| 9.8 | Scheduler envía automáticamente sin intervención | ✅ |
| 9.9 | Envío por Telegram | ✅ |
| 9.10 | Envío por canal Web (NotificacionWeb) | ✅ |

**Evidencia crítica:**
Prueba 9.8 verificada el 27 sep a las 18:42:02.
Recordatorio creado por bot a las 18:36, enviado automáticamente por cron a las 18:42.

---

# SLIDE 20 — Pruebas: Certificados (FASE K)

## 10. Certificados

| # | Prueba | Estado |
|---|---|---|
| 10.1 | Crear certificado con avisos 15/7/3/1/0 | ✅ |
| 10.2 | Certificado vencido se marca como completado | ✅ |
| 10.3 | Re-agendado al siguiente aviso del array | ✅ |
| 10.4 | Botón inline "Renovar" desde Telegram | ✅ |
| 10.5 | Registro en `certificado_historial` | ✅ |
| 10.6 | Nuevo ciclo se crea automáticamente (+1 año) | ✅ |
| 10.7 | Emojis de urgencia según días restantes | ✅ |
| 10.8 | Link de renovación guardado y mostrado | ✅ |

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
| 11.1 | `/help` muestra lista de comandos | ✅ |
| 11.2 | `/recordar <texto> \| <fecha>` crea recordatorio | ✅ |
| 11.3 | `/recordar` con formato inválido → mensaje de ayuda | 🟢 |
| 11.4 | `/recordar` con fecha pasada → rechazo | 🟢 |
| 11.5 | `/recordatorios` lista pendientes del usuario | ✅ |
| 11.6 | `/cancelar_recordatorio <id>` cancela pendiente | ✅ |
| 11.7 | `/certificados` lista certificados con días restantes | ✅ |
| 11.8 | `/nuevo_cert <nombre> \| <fecha>` registra certificado | ✅ |

## 12. Webhook e idempotencia

| # | Prueba | Estado |
|---|---|---|
| 12.1 | Webhook recibe updates y responde 200 OK | ✅ |
| 12.2 | Idempotencia: updates duplicados son ignorados | ✅ |
| 12.3 | Callback de botones inline funciona | ✅ |
| 12.4 | Ubicaciones compartidas se procesan correctamente | ✅ |

**Nota:** En el log de producción el tipo aparece como "otro" en vez de "command" (cosmético, no afecta funcionalidad).

---

# SLIDE 22 — Pruebas: Observer y Notificaciones Web

## 13. Observer de Instalaciones (FASE K)

| # | Prueba | Estado |
|---|---|---|
| 13.1 | Cambio a `completada` crea recordatorio por admin | ✅ |
| 13.2 | Cambio a `entrega` cierra previos + crea nuevo | ✅ |
| 13.3 | Cambio a `cancelada` cierra previos + crea nuevo | ✅ |
| 13.4 | No dispara si el estatus no cambió | ✅ |
| 13.5 | No dispara si no hay admins (log warning) | ✅ |

## 14. Notificaciones Web (FASE K)

| # | Prueba | Estado |
|---|---|---|
| 14.1 | Se crean registros en `notificaciones_web` al enviar | ✅ |
| 14.2 | Badge en navbar muestra conteo correcto | ✅ |
| 14.3 | Listado en `/notificaciones` carga correctamente | ✅ |
| 14.4 | Marcar como leída / todas como leídas funciona | ✅ |

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

1. 🟢 Ejecutar A5 (sincronizar timezone laptop) — 10 min
2. 🔴 Ejecutar A4 (blindar rutas sin middleware) — 30 min
3. 🔴 Ejecutar A6 (rotar Reverb secret) — 15 min
4. 🟡 A1 (tests del Procesador) — 4-6 h

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
