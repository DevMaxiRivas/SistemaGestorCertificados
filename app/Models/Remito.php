<?php

namespace App\Models;

// Auditoria
use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class Remito extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use HasFactory;

    protected $dates = [
        'fecha_emision',
        'fecha_recepcion',
        'fecha_registro',
    ];

    // Constantes de estado de eliminacion logica
    const ACTIVO = '1';
    const INACTIVO = '0';

    // Constantes de estado de control
    const CONTROL_PENDIENTE = 0;
    const CONTROL_APROBADO = 1;
    const CONTROL_RECHAZADO = 2;

    const ESTADOS = [
        self::CONTROL_PENDIENTE => 'Pendiente',
        self::CONTROL_APROBADO => 'Aprobado',
        self::CONTROL_RECHAZADO => 'Rechazado'
    ];

    // Nombre de la tabla que se conecta a este Modelo
    protected $fillable = [
        'id_proveedor',
        'id_pto_venta_prov',
        'nro_remito',
        'id_pto_venta',
        'nro_orden_compra',
        'fecha_emision',
        'fecha_recepcion',
        'url_remito',
        'observaciones',
        'id_empleado',
        'estado',
        'activo',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    public function productos()
    {
        return $this->belongsToMany(DetalleRemito::class, 'detalles_remitos', 'id_remito', 'id_producto');
    }

    public function punto_venta_proveedor()
    {
        return $this->belongsTo(PuntoVentaProveedor::class, 'id_pto_venta_prov');
    }

    public function punto_venta()
    {
        return $this->belongsTo(PuntoVenta::class, 'id_pto_venta');
    }

    public function certificados()
    {
        return $this->hasMany(Certificado::class, 'id_remito')->where('certificados.activo', Certificado::ACTIVO);
    }

    public function detalles_remito()
    {
        return $this->hasMany(DetalleRemito::class, 'id_remito')->where('detalles_remitos.activo', DetalleRemito::ACTIVO);
    }

    // Relacion Muchos a Muchos
    public function lotes()
    {
        return $this->hasManyThrough(
            Lote::class,
            DetalleRemito::class,
            'id_remito', // Foreign key on the detalle_remito table...
            'id_det_remito', // Foreign key on the lotes table...
            'id', // Local key on the remito table...
            'id' // Local key on the detalle_remito table...
        )->where('lotes.activo', Lote::ACTIVO);
    }

    public function es_eliminable()
    {
        return 
            $this->detalles_remito()
            ->where('peso', '!=', null)
            ->where('cantidad','!=', null)
            ->count() == 0 
            &&$this->lotes->count() == 0 
            && $this->certificados->count() == 0
            ; 
    }

    public function empleado()
    {
        return $this->belongsTo(User::class, 'id_empleado');
    }
    
    public function eliminar_detalles_remito()
    {
        $detalles = $this->detalles_remito;
        foreach($detalles as $detalle){
            $detalle->eliminar();
        }
    }

    public function eliminar()
    {
        if(!$this->es_eliminable()) {
            return false;
        }
        $this->eliminar_detalles_remito();
        $this->activo = Remito::INACTIVO;
        $this->update();
        return true;
    }

    public function esEditable()
    {
        // Asegúrate de que $this->fecha_recepcion sea una instancia de Carbon
        $fechaRecepcion = Carbon::parse($this->fecha_recepcion);
        $diferenciaDias = now()->diffInDays($fechaRecepcion);
    
        return $diferenciaDias > -10;
    }

    public static function obtener($puntos_venta = null)
    {
        return Remito::where('remitos.activo', Remito::ACTIVO)
            ->where('remitos.estado', Remito::CONTROL_APROBADO)
            ->orderBy('remitos.fecha_recepcion', 'desc');
    }

    public static function obtener_desde($fecha_recepcion_desde, $puntos_venta = null)
    {
        $query = Remito::where('remitos.activo', Remito::ACTIVO)
        ->where('remitos.fecha_recepcion', '>=', $fecha_recepcion_desde);
        // ->where('remitos.estado', Remito::CONTROL_APROBADO);
        
        if(!empty($puntos_venta) && count($puntos_venta) > 0){
            $query = $query->whereIn('remitos.id_pto_venta', $puntos_venta);
        }

        $query = $query->orderBy('remitos.fecha_recepcion', 'desc');
        return $query;
    }
        
    protected static function join_detalles_remitos(
        $query,
        $productos = null,
        $peso = null,
        $cantidad = null,
    ){
        $query = $query->join('detalles_remitos', 'remitos.id', '=', 'detalles_remitos.id_remito');
        $query = $query->where('detalles_remitos.activo', DetalleRemito::ACTIVO);

        if(!empty($productos)) $query = $query->whereIn('detalles_remitos.id_producto', $productos);
        if(!empty($peso)) $query = $query->where('detalles_remitos.peso', '>=', $peso);
        if(!empty($cantidad)) $query = $query->where('detalles_remitos.cantidad', '>=', $cantidad);
        
        return $query;
    }

    public static function remitos_filtrar(
        $proveedor = null,
        $ptos_ventas_prov = null,
        $nro_remito = null,
        $ptos_ventas = null,
        $nro_orden_compra = null,
        $fecha_recepcion_desde = null, 
        $fecha_recepcion_hasta = null, 
    ){
        $query = Remito::where('remitos.activo', Remito::ACTIVO)
            ->where('remitos.estado', Remito::CONTROL_APROBADO);

        if(!empty($proveedor)) $query = $query->where('remitos.id_proveedor', $proveedor);
        if(!empty($ptos_ventas_prov)) $query = $query->whereIn('remitos.id_pto_venta_prov', $ptos_ventas_prov);
        if(!empty($nro_remito)) $query = $query->where('remitos.nro_remito', 'like', '%'.$nro_remito.'%');
        if(!empty($ptos_ventas)) $query = $query->whereIn('remitos.id_pto_venta', $ptos_ventas);
        if(!empty($nro_orden_compra)) $query = $query->where('remitos.nro_orden_compra', 'like', '%'.$nro_orden_compra.'%');
        if(!empty($fecha_recepcion_desde)) $query = $query->where('remitos.fecha_recepcion', '>=', $fecha_recepcion_desde);
        if(!empty($fecha_recepcion_hasta)) $query = $query->where('remitos.fecha_recepcion', '<', $fecha_recepcion_hasta);

        return $query;
    }

    public static function detalles_remitos_filtrar(
        $proveedor = null,
        $ptos_ventas_prov = null,
        $nro_remito = null,
        $ptos_ventas = null,
        $nro_orden_compra = null,
        $fecha_recepcion_desde = null, 
        $fecha_recepcion_hasta = null, 
        $productos = null
    ){
        return 
            self::join_detalles_remitos(
                query : self::remitos_filtrar(
                    proveedor: $proveedor,
                    ptos_ventas_prov: $ptos_ventas_prov,
                    nro_remito: $nro_remito,
                    ptos_ventas: $ptos_ventas,
                    nro_orden_compra: $nro_orden_compra,
                    fecha_recepcion_desde: $fecha_recepcion_desde,
                    fecha_recepcion_hasta: $fecha_recepcion_hasta,
                ),
                productos: $productos,
            );
    }

    public static function remitos_filtro_avanzado(
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

        $query = Proveedor::join_remitos(
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
        )->select(
            'proveedores.razon_social as proveedor',
            'remitos.id as id_remito',
            'remitos.id_pto_venta_prov',
            'puntos_venta_proveedores.sucursal as sucursal_prov',
            'puntos_venta_proveedores.nro_pto_venta as pto_venta_prov',
            'remitos.nro_remito',
            'remitos.id_pto_venta',
            'puntos_venta.sucursal as sucursal',
            'puntos_venta.nro_pto_venta as pto_venta',
            'remitos.nro_orden_compra',
            'remitos.fecha_emision',
            'remitos.fecha_recepcion',
            'remitos.fecha_registro',
        )->groupBy(
            'proveedores.razon_social',
            'remitos.id',
            'remitos.id_pto_venta_prov',
            'puntos_venta.sucursal',
            'puntos_venta.nro_pto_venta',
            'remitos.nro_remito',
            'remitos.id_pto_venta',
            'puntos_venta_proveedores.sucursal',
            'puntos_venta_proveedores.nro_pto_venta',
            'remitos.nro_orden_compra',
            'remitos.fecha_emision',
            'remitos.fecha_recepcion',
            'remitos.fecha_registro',
        );

        Log::info('query', [
            'query' => $query->toSql(),
            'bindings' => $query->getBindings(),
        ]);
        $filas = $query->get();

        return $filas;
    }

    public static function tn_recibidas_periodo_mensual($fecha_recepcion_desde, $fecha_recepcion_hasta)
    {
        return DB::table('remitos')
            ->join('detalles_remitos', 'detalles_remitos.id_remito', '=', 'remitos.id')
            ->where('remitos.activo', Remito::ACTIVO)
            ->where('remitos.estado', Remito::CONTROL_APROBADO)
            ->where('detalles_remitos.activo', '>=', DetalleRemito::ACTIVO)
            ->where('remitos.fecha_recepcion', '>=', $fecha_recepcion_desde)
            ->where('remitos.fecha_recepcion', '<', $fecha_recepcion_hasta)
            ->groupByRaw('YEAR(remitos.fecha_recepcion), MONTH(remitos.fecha_recepcion)')
            ->selectRaw('
                YEAR(remitos.fecha_recepcion) AS Anio,
                MONTH(remitos.fecha_recepcion) AS Mes,
                SUM(detalles_remitos.peso)/1000 AS peso_total
            ')
            ->orderByDesc('Anio')
            ->orderByDesc('Mes')
            ->get();
    }

    public static function obtener_historial_remitos($cantidad_registros = 5, $puntos_venta = null)
    {
        return PuntoVenta::join_remitos(
            query_remitos: Remito::remitos_filtrar(
                ptos_ventas: $puntos_venta,
            )
        )->orderBy('remitos.fecha_recepcion', 'desc')
        ->limit($cantidad_registros);
    }

    public static function obtener_ranking_tns_productos($fecha_recepcion_desde, $fecha_recepcion_hasta, $cantidad_registros = 3)
    {
        return DB::table('detalles_remitos')
            ->join('remitos', 'remitos.id', '=', 'detalles_remitos.id_remito')
            ->join('productos', 'productos.id', '=', 'detalles_remitos.id_producto')
            ->where('remitos.activo', Remito::ACTIVO)
            ->where('remitos.estado', Remito::CONTROL_APROBADO)
            ->where('detalles_remitos.activo', '>=', DetalleRemito::ACTIVO)
            ->where('remitos.fecha_recepcion', '>=', $fecha_recepcion_desde)
            ->where('remitos.fecha_recepcion', '<', $fecha_recepcion_hasta)
            ->groupBy('productos.id', 'productos.cod_prod')
            ->selectRaw('productos.cod_prod, SUM(detalles_remitos.peso)/1000 as tn_totales')
            ->orderByDesc('tn_totales')
            ->limit($cantidad_registros)
            ->get();
    }
}
