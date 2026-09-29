<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Sync idempotente del schema con el estado real de producción.
 *
 * Contexto:
 *   El proyecto arrastra drift entre migraciones y prod por cambios
 *   hechos a mano con ALTER TABLE que nunca se versionaron. Esta
 *   migración cierra esa brecha de forma idempotente (chequea
 *   hasTable / hasColumn antes de tocar nada).
 *
 *   - No-op en dev y prod (ya tienen todo).
 *   - Reparador en testing / entornos nuevos (migrate:fresh).
 *
 * Fecha: 2026-09-29
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->corregirTipos();
        $this->agregarColumnasFaltantes();
        $this->crearTablasHuerfanas();
    }

    public function down(): void
    {
        // No-op: solo traemos el esquema al estado de prod.
        // Rollback fiel no aporta valor y es riesgoso.
    }

    private function corregirTipos(): void
    {
        DB::statement('ALTER TABLE `instalaciones` MODIFY `ubicacion_actual` POINT NULL');
        DB::statement('ALTER TABLE `instalaciones` MODIFY `evidencia_inicio` MEDIUMBLOB NULL');
        DB::statement('ALTER TABLE `instalaciones` MODIFY `incidencias` MEDIUMBLOB NULL');
        DB::statement('ALTER TABLE `instalaciones` MODIFY `evidencia_fin` MEDIUMBLOB NULL');
        DB::statement('ALTER TABLE `inventario` MODIFY `imagen` MEDIUMBLOB NULL');
    }

    private function agregarColumnasFaltantes(): void
    {
        if (!Schema::hasColumn('instalaciones', 'nombre_instalacion')) {
            DB::statement("ALTER TABLE `instalaciones` ADD `nombre_instalacion` VARCHAR(255) NOT NULL DEFAULT 'Instalación'");
        }

        if (!Schema::hasColumn('inventario', 'imagen_url')) {
            DB::statement("ALTER TABLE `inventario` ADD `imagen_url` VARCHAR(255) NULL AFTER `imagen`");
        }
        if (!Schema::hasColumn('inventario', 'codigo_origen')) {
            DB::statement("ALTER TABLE `inventario` ADD `codigo_origen` VARCHAR(255) NULL");
        }
        if (!Schema::hasColumn('inventario', 'fecha_alta')) {
            DB::statement("ALTER TABLE `inventario` ADD `fecha_alta` DATETIME NULL");
        }
        if (!Schema::hasColumn('inventario', 'precio')) {
            DB::statement("ALTER TABLE `inventario` ADD `precio` DECIMAL(12,2) NULL");
        }

        if (!Schema::hasColumn('movimientos_inventario', 'comentarios')) {
            DB::statement("ALTER TABLE `movimientos_inventario` ADD `comentarios` TEXT NULL");
        }
    }

    private function crearTablasHuerfanas(): void
    {
        $this->crearCotizaciones();
        $this->crearCotizacionDetalles();
        $this->crearSalidaDetalle();
        $this->crearDevolucionDetalle();
        $this->crearGeocercas();
        $this->crearGeocercaAlertas();
        $this->crearGeocercaEstados();
        $this->crearInstalacionInstalador();
        $this->crearSolicitudesUbicacion();
        $this->crearTraccarDevices();
        $this->crearUbicacionesUsuarios();
    }

    private function crearCotizaciones(): void
    {
        if (Schema::hasTable('cotizaciones')) return;
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->string('folio', 20);
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('proyecto_id');
            $table->date('fecha_emision');
            $table->date('fecha_validez')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('iva', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->string('moneda', 10)->default('USD');
            $table->text('condiciones')->nullable();
            $table->enum('estatus', ['borrador','enviada','aprobada','rechazada','facturada'])->default('borrador');
            $table->string('creado_por');
            $table->timestamps();
        });
    }

    private function crearCotizacionDetalles(): void
    {
        if (Schema::hasTable('cotizacion_detalles')) return;
        Schema::create('cotizacion_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cotizacion_id');
            $table->unsignedBigInteger('inventario_id')->nullable();
            $table->string('descripcion');
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('importe', 12, 2);
            $table->timestamps();
        });
    }

    private function crearSalidaDetalle(): void
    {
        if (Schema::hasTable('salida_detalle')) return;
        Schema::create('salida_detalle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salida_id');
            $table->unsignedBigInteger('inventario_id');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 12, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    private function crearDevolucionDetalle(): void
    {
        if (Schema::hasTable('devolucion_detalle')) return;
        Schema::create('devolucion_detalle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('devolucion_id');
            $table->unsignedBigInteger('inventario_id');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 12, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    private function crearGeocercas(): void
    {
        if (Schema::hasTable('geocercas')) return;
        Schema::create('geocercas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->decimal('latitud', 10, 7);
            $table->decimal('longitud', 10, 7);
            $table->integer('radio')->comment('Radio en metros');
            $table->unsignedBigInteger('proyecto_id')->nullable();
            $table->unsignedBigInteger('instalacion_id')->nullable();
            $table->string('color', 7)->default('#FF0000');
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    private function crearGeocercaAlertas(): void
    {
        if (Schema::hasTable('geocerca_alertas')) return;
        Schema::create('geocerca_alertas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('geocerca_id');
            $table->unsignedBigInteger('usuario_id');
            $table->enum('tipo', ['entrada', 'salida']);
            $table->decimal('latitud', 10, 7);
            $table->decimal('longitud', 10, 7);
            $table->timestamp('fecha_hora')->useCurrent();
            $table->boolean('notificado')->default(false);
            $table->timestamps();
        });
    }

    private function crearGeocercaEstados(): void
    {
        if (Schema::hasTable('geocerca_estados')) return;
        Schema::create('geocerca_estados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('geocerca_id');
            $table->unsignedBigInteger('usuario_id');
            $table->enum('estado', ['dentro', 'fuera'])->default('fuera');
            $table->decimal('ultima_latitud', 10, 7)->nullable();
            $table->decimal('ultima_longitud', 10, 7)->nullable();
            $table->timestamp('ultima_actualizacion')->useCurrent();
            $table->timestamps();
        });
    }

    private function crearInstalacionInstalador(): void
    {
        if (Schema::hasTable('instalacion_instalador')) return;
        Schema::create('instalacion_instalador', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instalacion_id');
            $table->string('instalador_usuario');
            $table->boolean('es_principal')->default(false);
            $table->timestamps();
        });
    }

    private function crearSolicitudesUbicacion(): void
    {
        if (Schema::hasTable('solicitudes_ubicacion')) return;
        Schema::create('solicitudes_ubicacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->string('chat_id', 100);
            $table->string('tipo', 20);
            $table->unsignedBigInteger('instalacion_id')->nullable();
            $table->timestamps();
        });
    }

    private function crearTraccarDevices(): void
    {
        if (Schema::hasTable('traccar_devices')) return;
        Schema::create('traccar_devices', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('uniqueId')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('lastUpdate')->nullable();
            $table->integer('positionId')->nullable();
            $table->integer('groupId')->nullable();
            $table->string('phone')->nullable();
            $table->string('model')->nullable();
            $table->string('contact')->nullable();
            $table->string('category')->nullable();
            $table->boolean('disabled')->nullable();
            $table->longText('attribs')->nullable();
            $table->timestamps();
        });
    }

    private function crearUbicacionesUsuarios(): void
    {
        if (Schema::hasTable('ubicaciones_usuarios')) return;
        Schema::create('ubicaciones_usuarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('instalacion_id')->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->timestamp('fecha_hora')->nullable();
            $table->string('tipo', 20)->nullable();
            $table->string('fuente', 50)->nullable();
            $table->longText('detalles')->nullable();
            $table->timestamps();
        });
    }
};
