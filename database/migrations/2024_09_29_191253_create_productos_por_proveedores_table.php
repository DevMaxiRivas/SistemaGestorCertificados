<?php

use App\Models\ProductoProveedor;
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
        Schema::create('productos_por_proveedor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_prod_empresa'); // BIGINT(20)
            $table->unsignedBigInteger('id_proveedor'); // BIGINT(20)
            $table->string('cod_prod_prov');
            $table->string('descripcion');
            $table->enum('activo', [ProductoProveedor::ACTIVO, ProductoProveedor::INACTIVO])->comment('1: Activo, 0: Inactivo')->default(ProductoProveedor::ACTIVO);

            $table->foreign('id_prod_empresa')->references('id')->on('productos');
            $table->foreign('id_proveedor')->references('id')->on('proveedores');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos_por_proveedor');
    }
};
