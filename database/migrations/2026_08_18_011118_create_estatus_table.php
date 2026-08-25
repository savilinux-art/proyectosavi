<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('estatus', function (Blueprint $table) {
            $table->id();
            $table->string('estatus')->unique();
            $table->enum('tipo', ['venta', 'instalacion', 'proyecto']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estatus');
    }
};