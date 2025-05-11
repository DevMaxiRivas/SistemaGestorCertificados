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
        Schema::create('puntos_venta_proveedores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_provincia');
            $table->unsignedBigInteger('id_proveedor');
            $table->unsignedBigInteger('nro_pto_venta');
            $table->string('sucursal');
            $table->string('direccion')->nullable();
            $table->enum('activo', [PuntoVentaProveedor::ACTIVO, PuntoVentaProveedor::INACTIVO])->comment('1: Activo, 0: Inactivo')->default(PuntoVentaProveedor::ACTIVO);
            $table->timestamps();

            $table->foreign('id_provincia')->references('id')->on('provincias');
            $table->foreign('id_proveedor')->references('id')->on('proveedores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puntos_venta_proveedores');
    }
};