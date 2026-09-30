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