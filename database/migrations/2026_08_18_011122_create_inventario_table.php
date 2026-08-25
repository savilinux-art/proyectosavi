<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventario', function (Blueprint $table) {
            $table->id();
            $table->integer('existencia')->default(0);
            $table->string('modelo');
            $table->string('descripcion');
            $table->string('marca');
            $table->string('categoria');
            $table->foreign('categoria')->references('nombre_categoria')->on('categorias');
            $table->string('almacen');
            $table->string('apea');
            $table->binary('imagen')->nullable();
            $table->dateTime('fecha_modificacion');
            $table->string('comentarios')->nullable();
            $table->string('apartados')->nullable();
            $table->string('cantidad_apartados')->nullable();
            $table->string('modificado_por');
            $table->foreign('modificado_por')->references('usuario')->on('usuarios');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario');
    }
};