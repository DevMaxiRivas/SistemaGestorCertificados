<?php

use App\Models\Remito;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('remitos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_proveedor');
            
            $table->unsignedBigInteger('id_pto_venta_prov');
            $table->unsignedBigInteger('nro_remito');
            
            $table->unsignedBigInteger('id_pto_venta');
            $table->unsignedBigInteger('nro_orden_compra');

            $table->smallInteger('estado')->default(Remito::CONTROL_PENDIENTE);

            $table->string('url_remito')->nullable();
            $table->date('fecha_recepcion');
            $table->date('fecha_emision');
            $table->timestamp('fecha_registro')->default(now());
            $table->text('observaciones')->nullable();
            $table->enum('activo', [Remito::ACTIVO, Remito::INACTIVO])->comment('1: Activo, 0: Inactivo')->default(Remito::ACTIVO);

            $table->unsignedBigInteger('id_empleado')->nullable();
            $table->timestamps();
            
            $table->foreign('id_pto_venta_prov')->references('id')->on('puntos_venta_proveedores');
            $table->foreign('id_pto_venta')->references('id')->on('puntos_venta');
            $table->foreign('id_proveedor')->references('id')->on('proveedores');
            $table->foreign('id_empleado')->references('id')->on('users');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remitos');
    }
};
