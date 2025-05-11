<?php

namespace App\Models;

// Auditoria
use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

class PuntoVenta extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    
    // Constantes de estado de la devolucions
    const ACTIVO = '1';
    const INACTIVO = '0';

    const funciones_DB_equivalentes = [
            'diferencia_fechas_timestamp' => [
                'sqlsrv' => 'DATEDIFF',
                'mysql' => 'TIMESTAMPDIFF'
            ]
        ];

    // Nombre de la tabla que se conecta a este Modelo
    protected $table = 'puntos_venta';

    // Nombres de las columnas que son modificables
    protected $fillable = [
        'id_provincia',
        'nro_pto_venta',
        'sucursal',
        'direccion',
        'activo',
    ];

    public function eliminar()
    {
        $this->activo = PuntoVenta::INACTIVO;
        $this->update();
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'id_provincia');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_por_pto_venta', 'id_pto_venta', 'id_user');
    }

    public static function obtener()
    {
        return PuntoVenta::where('activo', PuntoVenta::ACTIVO)->get();
    }

    public static function obtenerPorNroPtoVenta($pto_venta){
        try {
            return PuntoVenta::where('activo', PuntoVenta::ACTIVO)
                ->where('nro_pto_venta', $pto_venta)
                ->first();
        } catch (\Exception $e) {
            Log::alert('No se pudo obtener el punto de venta');
            return null;
        }
    }

    public static function join_remitos($query_remitos)
    {
        return $query_remitos->join('puntos_venta', 'remitos.id_pto_venta', '=', 'puntos_venta.id')
            ->where('puntos_venta.activo', PuntoVenta::ACTIVO);
    }

    public static function minutos_promedio_de_registro($fecha_recepcion_desde, $fecha_recepcion_hasta)
    {
        $funcion_diferencia_db = self::funciones_DB_equivalentes['diferencia_fechas_timestamp'][Config::get('database.default')];
        return DB::table(function ($query) use ($fecha_recepcion_desde, $fecha_recepcion_hasta, $funcion_diferencia_db) {
            $query->from('remitos')
                ->selectRaw('
                    remitos.id,
                    remitos.id_pto_venta,
                    remitos.nro_remito,
                    puntos_venta.sucursal,
                    remitos.fecha_registro,
                    MAX(lotes.updated_at) AS fecha_lote_mas_reciente, 
                    '.$funcion_diferencia_db.'(MINUTE, remitos.fecha_registro, MAX(lotes.updated_at)) as minutos
                ')
                ->join('detalles_remitos', 'remitos.id', '=', 'detalles_remitos.id_remito')
                ->join('lotes', 'detalles_remitos.id', '=', 'lotes.id_det_remito')
                ->join('puntos_venta', 'remitos.id_pto_venta', '=', 'puntos_venta.id')
                ->where('remitos.fecha_recepcion', '>=', $fecha_recepcion_desde)
                ->where('remitos.fecha_recepcion', '<', $fecha_recepcion_hasta)
                ->groupBy(
                    'remitos.id',
                    'remitos.id_pto_venta',
                    'remitos.nro_remito',
                    'remitos.fecha_registro',
                    'puntos_venta.sucursal'
                );
        }, 'auxTable')
        ->selectRaw('CAST(AVG(auxTable.minutos) AS INT) as minutos_promedio_registro, auxTable.sucursal, auxTable.id_pto_venta')
        ->groupBy('auxTable.id_pto_venta', 'auxTable.sucursal')
        ->orderByRaw('AVG(auxTable.minutos)')
        ->get();
    }

    public static function tn_recibidas_por_sucursal($fecha_recepcion_desde, $fecha_recepcion_hasta)
    {
        return DB::table('detalles_remitos')
            ->join('remitos', 'remitos.id', '=', 'detalles_remitos.id_remito')
            ->join('puntos_venta', 'puntos_venta.id', '=', 'remitos.id_pto_venta')
            ->where('remitos.fecha_recepcion', '>=', $fecha_recepcion_desde)
            ->where('remitos.fecha_recepcion', '<', $fecha_recepcion_hasta)
            ->groupBy('puntos_venta.id', 'puntos_venta.sucursal')
            ->selectRaw('puntos_venta.id as id_pto_venta, puntos_venta.sucursal, SUM(detalles_remitos.peso)/1000 as peso_total')
            ->get();
    }

}