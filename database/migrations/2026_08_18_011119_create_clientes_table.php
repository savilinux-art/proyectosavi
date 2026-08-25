<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('rfc')->unique();
            $table->string('razon_social');
            $table->string('nombre_proyecto');
            $table->string('regimen_fiscal');
            $table->mediumBlob('constancia_situacion_fiscal')->nullable();
            $table->integer('codigo_postal');
            $table->string('correo_electronico');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};