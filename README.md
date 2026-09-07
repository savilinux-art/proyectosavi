<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Acerca de  Laravel

Laravel es un framework para aplicaciones web con una sintaxis expresiva y elegante. Creemos que el desarrollo debe ser una experiencia creativa y agradable para resultar verdaderamente gratificante. Laravel elimina las complicaciones del desarrollo al facilitar tareas comunes en muchos proyectos web, tales como:

- [Motor de enrutamiento simple y rápido](https://laravel.com/docs/routing).
- [Potente contenedor de inyección de dependencias](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Aprendiendo Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).



# 🏗️ ProyectoSAVI - Sistema de Gestión de Instalaciones Tecnológicas

Sistema empresarial para la gestión integral de **ventas, proyectos, instalaciones, inventario y seguimiento en tiempo real de instaladores** mediante GPS y Telegram.

---

## 🎯 **Objetivo**

Gestionar el ciclo completo de una empresa de instalaciones tecnológicas, desde la **prospección de ventas** hasta la **finalización de instalaciones**, con seguimiento de **inventario** y **ubicaciones en tiempo real**.

---

## 🧱 **Arquitectura Técnica**

| Componente | Tecnología |
|------------|------------|
| **Backend** | Laravel 13 (PHP 8.3.6) |
| **Base de Datos** | MariaDB 10.11 |
| **Frontend** | Blade + Bootstrap 5 + jQuery + DataTables + Leaflet.js |
| **Autenticación** | Sesiones nativas + Middleware personalizado |
| **WebSockets** | Laravel Reverb (tiempo real) |
| **Notificaciones** | Telegram Bot API |
| **GPS / Seguimiento** | Traccar (servidor GPS) |
| **Mapas** | Leaflet.js + OpenStreetMap |
| **Exportaciones** | Laravel Excel, Dompdf (PDF) |
| **Control de acceso** | Sistema propio basado en roles y permisos |

---

## 📂 **Estructura de Base de Datos**

### 🧩 Principales Tablas y Relaciones

```sql
usuarios (id, usuario, nombre, correo, telegram_chat_id, traccar_device_id, rol)
roles (id, rol)
permisos (id, nombre, slug, modulo)
permiso_rol (rol, permiso_id, permitido)

ventas (id, titulo_venta, nombre_proyecto, monto_venta, estatus, vendedor)
proyectos (id, nombre_proyecto, correo_electronico, ubicacion)

instalaciones (id, nombre_proyecto, fecha_hora_inicio, estatus_instalacion, check_list)
instalacion_instalador (instalacion_id, instalador_usuario, es_principal)

inventario (id, existencia, modelo, descripcion, marca, categoria, imagen_url)
movimientos_inventario (id, inventario_id, entrada, salida, ajuste)
salidas_inventario (id, nombre_proyecto, entregado_por, entregado_a, productos)
devoluciones_inventario (id, nombre_proyecto, devuelto_por, recibido_por, productos)

ubicaciones_usuarios (id, usuario_id, latitud, longitud, tipo, instalacion_id)
solicitudes_ubicacion (id, usuario_id, chat_id, tipo, instalacion_id)

geocercas (id, nombre, latitud, longitud, radio, proyecto_id)
geocerca_alertas (id, geocerca_id, usuario_id, tipo, fecha_hora)

traccar_devices (id, name, uniqueId, lastUpdate)