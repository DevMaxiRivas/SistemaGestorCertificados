<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Lote;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_det_remito');
            $table->string('nro_lote');
            $table->decimal('peso', 10, 2);
            $table->enum('activo', [Lote::ACTIVO, Lote::INACTIVO])->comment('1: Activo, 0: Inactivo')->default(Lote::ACTIVO);
            $table->unsignedBigInteger('id_certificado')->nullable();
            $table->timestamps();

            $table->foreign('id_det_remito')->references('id')->on('detalles_remitos');
            $table->foreign('id_certificado')->references('id')->on('certificados');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
