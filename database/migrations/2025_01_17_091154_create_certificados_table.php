<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Certificado;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    // Por decision administrativa no se vinculan los certificados con los productos
    // Sino que unicamente con los remitos
    public function up(): void
    {
        Schema::create('certificados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_remito');
            $table->string('url_certificado'); // Ruta del archivo PDF en el servidor
            $table->enum('activo', [Certificado::ACTIVO, Certificado::INACTIVO])->comment('1: Activo, 0: Inactivo')->default(Certificado::ACTIVO);
            $table->timestamps();

            $table->foreign('id_remito')->references('id')->on('remitos');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificados');
    }
};
