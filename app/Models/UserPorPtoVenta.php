<?php

namespace App\Models;

// Auditoria
use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class UserPorPtoVenta extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    
    // Constantes de estado de la devolucions
    const ACTIVO = '1';
    const INACTIVO = '0';

    // Nombre de la tabla que se conecta a este Modelo
    protected $table = 'users_por_pto_venta';

    // Nombres de las columnas que son modificables
    protected $fillable = [
        'id_user',
        'id_pto_venta',
        'activo',
    ];

    public function eliminar()
    {
        return $this->activo = self::INACTIVO;
    }
    
}