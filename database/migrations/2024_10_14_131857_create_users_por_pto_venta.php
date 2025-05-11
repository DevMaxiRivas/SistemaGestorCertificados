<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\UserPorPtoVenta;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users_por_pto_venta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_pto_venta');
            $table->enum('activo', [UserPorPtoVenta::ACTIVO, UserPorPtoVenta::INACTIVO])->comment('1: Activo, 0: Inactivo')->default(UserPorPtoVenta::ACTIVO);
        
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_pto_venta')->references('id')->on('puntos_venta');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_por_pto_venta');
    }
};
