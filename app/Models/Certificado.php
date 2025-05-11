<?php

namespace App\Models;

// Auditoria
use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Certificado extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    
    // Constantes de estado de la devolucions
    const ACTIVO = '1';
    const INACTIVO = '0';

    // Nombre de la tabla que se conecta a este Modelo
    protected $table = 'certificados';

    // Nombres de las columnas que son modificables
    protected $fillable = [
        'id_remito',
        'url_certificado'
    ];

    public function es_eliminable()
    {
        return $this->lotes()->count() == 0;
    }

    public function eliminar()
    {
        $this->activo = Certificado::INACTIVO;
        $this->update();
    }

    public function remito()
    {
        return $this->belongsTo(Remito::class, 'id_remito');
    }

    public function lotes()
    {
        return $this->hasMany(Lote::class, 'id_certificado');
    }

    public static function cantidadCertificadosPorRemito($id_remito) 
    {
        return Certificado::where('id_remito', $id_remito)
            ->where('activo', Certificado::ACTIVO)
            ->count();
    }

    public static function join_lotes($query_lotes)
    {
        $query_lotes = $query_lotes->join('certificados', 'certificados.id', '=', 'lotes.id_certificado');
        $query_lotes = $query_lotes->where('certificados.activo', self::ACTIVO);
        return $query_lotes;
    }

    public static function filtrarCertificados(
        $proveedor,
        $ptos_ventas_prov,
        $nro_remito,
        $ptos_ventas,
        $nro_orden_compra,
        $fecha_recepcion_desde,
        $fecha_recepcion_hasta,
        $productos,
        $nro_lote
    )
    {
        $query = Remito::remitos_filtrar(
            proveedor: $proveedor,
            ptos_ventas_prov: $ptos_ventas_prov,
            nro_remito: $nro_remito,
            ptos_ventas: $ptos_ventas,
            nro_orden_compra: $nro_orden_compra,
            fecha_recepcion_desde: $fecha_recepcion_desde,
            fecha_recepcion_hasta: $fecha_recepcion_hasta,
        );
        
        $query = PuntoVenta::join_remitos(
            query_remitos: $query,
        );
        $query = PuntoVentaProveedor::join_remitos(
            query_remitos: $query,
        );

        $query = DetalleRemito::join_remitos(
            query_remitos: $query,
            productos: $productos,
            peso: null,
            cantidad: null,
        );

        $query = Producto::join_detalles_remitos(
            query_detalles_remitos: $query,
        );

        $query = Lote::join_detalles_remitos(
            query_detalles_remitos: $query,
            nro_lote: $nro_lote,
        );

    
        $filas = Certificado::join_lotes(
            query_lotes: $query,
        )->select(
            'certificados.id as id_certificado',
            'remitos.id as id_remito',
            'remitos.id_pto_venta_prov',
            'puntos_venta_proveedores.nro_pto_venta as pto_venta_prov',
            'remitos.nro_remito',
            'remitos.id_pto_venta',
            'puntos_venta.nro_pto_venta as pto_venta',
            'remitos.nro_orden_compra',
            'remitos.fecha_recepcion',
            'detalles_remitos.id_producto as id_producto',
            'productos.descripcion as producto'
        )->groupBy(
            'certificados.id',
            'remitos.id',
            'remitos.id_pto_venta_prov',
            'puntos_venta.nro_pto_venta',
            'remitos.nro_remito',
            'remitos.id_pto_venta',
            'puntos_venta_proveedores.nro_pto_venta',
            'remitos.nro_orden_compra',
            'remitos.fecha_recepcion',
            'detalles_remitos.id_producto',
            'productos.descripcion'
        )->get();

        return $filas;
    }

    public static function contar()
    {
        return Certificado::where('activo', Certificado::ACTIVO)->count();
    }

    public static function contar_por_periodo($fecha_desde, $fecha_hasta)
    {
        return Certificado::where('activo', Certificado::ACTIVO)
            ->whereBetween('created_at', [$fecha_desde, $fecha_hasta])
            ->count();
    }

    public static function acumulados_por_periodos($periodos = 2)
    {
        return DB::table(function ($query) {
            $query->from(function ($subQuery) {
                $subQuery->from('certificados')
                    ->where('certificados.activo', Certificado::ACTIVO)
                    ->selectRaw('
                        YEAR(certificados.created_at) AS anio,
                        MONTH(certificados.created_at) AS mes,
                        COUNT(certificados.id) AS cantidad
                    ')
                    ->groupByRaw('YEAR(certificados.created_at), MONTH(certificados.created_at)');
            }, 'tablaAux')
            ->selectRaw('
                tablaAux.anio,
                tablaAux.mes,
                SUM(tablaAux.cantidad) OVER (ORDER BY tablaAux.anio, tablaAux.mes) AS cantidad_acumulada
            ');
        }, 'tablaAux2')
        ->orderByDesc('tablaAux2.anio')
        ->orderByDesc('tablaAux2.mes')
        ->limit($periodos)
        ->get();
    }
}
