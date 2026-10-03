# Flujos de negocio — ProyectoSAVI

Documento vivo. Cada flujo describe un recorrido end-to-end del sistema.
Actualizar en el mismo commit que cualquier cambio de lógica.

**Última actualización:** 30 sep 2026
**Ver también:** `MODELO-DATOS.md` (entidades y campos), `GLOSARIO.md` (términos).

---

## Cómo leer este documento

- Cada flujo tiene: **disparador**, **pasos**, **responsables**, **efectos secundarios**, **archivos clave**.
- Los disparadores automáticos (Observers, Procesador, Webhooks) van marcados con ⚡.
- Los pasos manuales (usuario en UI) van marcados con 👤.
- Los archivos referenciados se citan con ruta y, cuando aplica, número de línea.

**Índice:**

1. Autenticación y sesión
2. Cadena comercial: Venta → Proyecto → Instalación
3. Cambio de estatus de instalación (Observer)
4. Recordatorios: creación, procesamiento y recurrencia
5. Certificados: avisos de vencimiento
6. Inventario: salida y devolución
7. Telegram: captura de ubicación y webhook
8. Geocercas: alertas de entrada/salida

## Infraestructura de acceso (sesión 01-oct-2026)

### Entornos

| Entorno | Ubicación | MariaDB | PHP | Estado |
|---|---|---|---|---|
| **Dev** | Laptop local | 11.8.9 | 8.3.33 | Schema sí, datos **no** |
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

---

# 1. Autenticación y sesión

⚠️ **Este proyecto NO usa el sistema de auth estándar de Laravel.**

**Ver**: `docs/auth/LOGICA-AUTENTICACION.md` (referencia: doc v17, secciones 1-6).

**Resumen:**

- ❌ No se usa `Auth::login()`, `Auth::attempt()`, `Auth::user()`.
- ✅ Se usa sesión custom vía `Session::put('user_usuario', ...)`.

**Flujo de login:**

1. 👤 Usuario entra a `/` → `AuthController@showLogin`.
2. 👤 Ingresa usuario + contraseña.
3. POST `/login` → `AuthController@login`.
4. El controlador verifica contra `usuarios.contraseña` (hash bcrypt).
5. En éxito, setea 3 claves de sesión:
   ```php
   Session::put('user_id',      $usuario->id);
   Session::put('user_usuario', $usuario->usuario);
   Session::put('user_rol',     $usuario->rol);

   2.1 Añadir al índice (o al final del documento)
markdown

## Flujos de inventario y trazabilidad
→ Ver detalle completo en docs/negocio/INVENTARIO-Y-TRAZABILIDAD.md §3

### Resumen ejecutivo

**Flujo end-to-end:**

Venta → Cotización → Reserva → Salida → Entrega → Instalación → Cierre
text


**Sub-flujos:**

| # | Flujo | Doc fuente |
|---|---|---|
| 3.0 | End-to-end general | §3.0 |
| 3.1 | Estados de cotización | §3.1 |
| 3.2 | Cotización → Reserva | §3.2 |
| 3.3 | Salida de inventario (remisión PDF) | §3.3 |
| 3.4 | Devolución (misma APEA, 3 estados) | §3.4 |
| 3.5 | Entrega al cliente (firma) | §3.5 |
| 3.6 | Cierre de proyecto (mano_obra) | §3.6 |
| 3.7 | Venta → Cotización → Proyecto | §3.7 |
| 3.8 | Liberación de apartado | §3.8 |

**Reglas clave (resumen):**

- Reserva se dispara al **aceptar** cotización (manual).
- Salida descuenta `existencia` **inmediatamente**.
- Cierre de proyecto es **automático** si no hay línea `mano_obra`.
- Entrega al cliente transfiere responsabilidad (firma PDF).
- Liberación de apartado es **acción manual** (botón).