<?php

use App\Models\Producto;
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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('cod_prod');
            $table->string('descripcion');
            $table->text('descripcion_detallada'); // TEXT: Soporta hasta 65535 caracteres
            $table->decimal('peso_unitario', 20, 2); // DECIMAL(20, 2)
            $table->enum('activo', [Producto::ACTIVO, Producto::INACTIVO])->comment('1: Activo, 0: Inactivo')->default(Producto::ACTIVO);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
