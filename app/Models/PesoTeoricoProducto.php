<?php

namespace App\Models;

// Auditoria
use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PesoTeoricoProducto extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    // Casteos
    protected $casts = [
        'peso_teorico' => 'float'
    ];

    // Nombre de la tabla que se conecta a este Modelo
    protected $table = 'pesos_teoricos_productos';

    public $timestamps = false;

    // Nombres de las columnas que son modificables
    protected $fillable = [
        'id_producto',
        'peso_teorico',
        'fecha_modificacion',
    ];

}