<?php

namespace App\Models;

// Auditoria
use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PuntoVentaProveedor extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    // Constantes de estado de la devolucions
    const ACTIVO = '1';
    const INACTIVO = '0';

    // Nombre de la tabla que se conecta a este Modelo
    protected $table = 'puntos_venta_proveedores';

    // Nombres de las columnas que son modificables
    protected $fillable = [
        'id_provincia',
        'id_proveedor',
        'nro_pto_venta',
        'sucursal',
        'direccion',
        'activo',
    ];

    public $timestamps = true;

    public static function obtener()
    {
        return PuntoVentaProveedor::where('activo', PuntoVentaProveedor::ACTIVO)->get();
    }

    public static function obtener_por_proveedor(Proveedor $proveedor)
    {
        try {
            return PuntoVentaProveedor::where('activo', PuntoVentaProveedor::ACTIVO)
                ->where('id_proveedor', $proveedor->id)
                ->get();
        } catch (\Exception $e) {
            Log::alert('No se pudo obtener los puntos de venta proveedor');
            return null;
        }
    }

    public function eliminar()
    {
        $this->activo = PuntoVentaProveedor::INACTIVO;
        $this->update();
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'id_provincia');
    }

    // public function proveedor()
    // {
    //     return $this->belongsTo(Proveedor::class, 'id_proveedor');
    // }

    // <?php
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    public static function obtenerPorNroPtoVenta($nro_pto_venta, $id_proveedor)
    {
        try {
            return PuntoVentaProveedor::where('activo', PuntoVentaProveedor::ACTIVO)
                ->where('nro_pto_venta', $nro_pto_venta)
                ->where('id_proveedor', $id_proveedor)
                ->first();
        } catch (\Exception $e) {
            Log::alert('No se pudo obtener el punto de venta proveedor');
            return null;
        }
    }

    public static function join_remitos($query_remitos)
    {
        $query_remitos = $query_remitos->join('puntos_venta_proveedores', 'puntos_venta_proveedores.id', '=', 'remitos.id_pto_venta_prov');
        $query_remitos = $query_remitos->where('puntos_venta_proveedores.activo', self::ACTIVO);
        return $query_remitos;
    }
}
