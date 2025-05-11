<?php

namespace App\Models;

// Auditoria
use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Lote extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    
    // Constantes de estado de la devolucions
    const ACTIVO = '1';
    const INACTIVO = '0';

    // Nombre de la tabla que se conecta a este Modelo
    protected $table = 'lotes';

    // Nombres de las columnas que son modificables
    protected $fillable = [
        'id_det_remito',
        'nro_lote',
        'peso',
        'id_certificado',
        'activo'
    ];

    public function eliminar()
    {
        $this->activo = Lote::INACTIVO;
        $this->update();
    }

    public function detalle_remito()
    {
        return $this->belongsTo(DetalleRemito::class, 'id_det_remito');
    }

    public function certificado()
    {
        return $this->belongsTo(Certificado::class, 'id_certificado');
    }

    public static function eliminarPorDetalleRemito(DetalleRemito $detalleRemito)
    {
        return Lote::where('id_det_remito', $detalleRemito->id)->update(['activo' => Lote::INACTIVO]);
    }
    
    public static function join_detalles_remitos(
        $query_detalles_remitos,
        $nro_lote = null,
        $peso = null
    )
    {
        $query_detalles_remitos = $query_detalles_remitos->join('lotes', 'lotes.id_det_remito', '=', 'detalles_remitos.id')
            ->where('lotes.activo', Lote::ACTIVO);

        if(!empty($nro_lote)) $query_detalles_remitos = $query_detalles_remitos->where('lotes.nro_lote', 'like', "%$nro_lote%");
        if(!empty($peso)) $query_detalles_remitos = $query_detalles_remitos->where('lotes.peso', '>=', $peso);
        
        return $query_detalles_remitos;
    }
}
