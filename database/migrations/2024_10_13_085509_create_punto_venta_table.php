<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PuntoVentaProveedor;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('puntos_venta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_provincia');
            $table->unsignedSmallInteger('nro_pto_venta');
            $table->string('sucursal');
            $table->string('direccion');
            $table->enum('activo', [PuntoVentaProveedor::ACTIVO, PuntoVentaProveedor::INACTIVO])->comment('1: Activo, 0: Inactivo')->default(PuntoVentaProveedor::ACTIVO);
            $table->timestamps();

            $table->foreign('id_provincia')->references('id')->on('provincias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puntos_venta');
    }
};