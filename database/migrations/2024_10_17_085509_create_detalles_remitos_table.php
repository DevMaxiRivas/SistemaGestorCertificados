<?php

use App\Models\DetalleRemito;
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
        Schema::create('detalles_remitos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_remito');
            $table->unsignedBigInteger('id_producto');
            $table->decimal('peso', 20, 2)->nullable();
            $table->unsignedBigInteger('cantidad')->nullable();
            $table->enum('activo', [DetalleRemito::ACTIVO, DetalleRemito::INACTIVO])->comment('1: Activo, 0: Inactivo')->default(DetalleRemito::ACTIVO);
            $table->timestamps();

            $table->foreign('id_remito')->references('id')->on('remitos');
            $table->foreign('id_producto')->references('id')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_remitos');
    }
};