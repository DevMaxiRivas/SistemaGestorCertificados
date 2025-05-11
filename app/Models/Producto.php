<?php

namespace App\Models;

// Auditoria
use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Producto extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    // Estados del producto
    const ACTIVO = '1';
    const INACTIVO = '0';

    // Nombre de la tabla que se conecta a este Modelo
    protected $table = 'productos';

    // Nombres de las columnas que son modificables
    protected $fillable = [
        'cod_prod',
        'descripcion',
        'descripcion_detallada',
        'peso_unitario',
        'activo'
    ];
    
    // Casteos
    protected $casts = [
        'peso_unitario' => 'double',
    ];

    const FILTROS = [
        'Por Código de Producto' => 'cod_prod',
        'Por Descripción' =>'descripcion'
    ];

    public function pesos_teoricos()
    {
        return $this->hasMany(PesoTeoricoProducto::class, 'id_producto')->orderBy('fecha_modificacion', 'desc');
    }

    public function es_eliminable()
    {
        $hay_pesos_teoricos = $this->pesos_teoricos()->count() > 0;
        $hay_productos_proveedores = ProductoProveedor::where('id_prod_empresa', $this->id)->where('activo', ProductoProveedor::ACTIVO)->count() > 0;
        $hay_detalles_remitos = DetalleRemito::where('id_prod', $this->id)->where('activo', DetalleRemito::ACTIVO)->count() > 0;

        return !$hay_pesos_teoricos && !$hay_productos_proveedores && !$hay_detalles_remitos;
    }

    public function eliminar()
    {
        if(!$this->es_eliminable()){
            return false;
        }

        $this->activo = Producto::INACTIVO;
        $this->update();
        return true;
    }


    public static function existe($cod_prod)
    {
        return Producto::where('cod_prod', $cod_prod)
            ->where('activo', Producto::ACTIVO)
            ->first();
    }

    public function nuevoPesoTeorico()
    {
        $peso_teorico = new PesoTeoricoProducto();
        $peso_teorico->id_producto = $this->id;
        $peso_teorico->peso_teorico = $this->peso_unitario;
        $peso_teorico->save();
    }

    public function obtenerVariacionesPorPeriodo($proveedor, $fecha_desde = null, $fecha_hasta = null)
    {
        $subquery = DB::table('remitos')
            ->join('detalles_remitos', 'remitos.id', '=', 'detalles_remitos.id_remito')
            ->select(
                DB::raw('YEAR(remitos.fecha_registro) AS anio'),
                DB::raw('MONTH(remitos.fecha_registro) AS mes'),
                DB::raw('SUM(detalles_remitos.peso) AS suma_ponderada_peso_balanza'),
                DB::raw('SUM(detalles_remitos.cantidad) AS cantidad')
            )
            ->where('remitos.activo', Remito::ACTIVO)
            ->where('detalles_remitos.activo', DetalleRemito::ACTIVO)
            ->where('remitos.id_proveedor', $proveedor)
            ->where('detalles_remitos.id_producto', $this->id)
            ->whereNotNull('detalles_remitos.cantidad')
            ->whereNotNull('detalles_remitos.peso')
            ->whereBetween('remitos.fecha_registro', [$fecha_desde, $fecha_hasta])
            ->groupBy(DB::raw('YEAR(remitos.fecha_registro)'))
            ->groupBy(DB::raw('MONTH(remitos.fecha_registro)'));

        $resultados = DB::table(DB::raw("({$subquery->toSql()}) as tablaAuxiliar"))
            ->mergeBindings($subquery)
            ->select(
                'tablaAuxiliar.anio',
                'tablaAuxiliar.mes',
                'tablaAuxiliar.cantidad',
                DB::raw('tablaAuxiliar.suma_ponderada_peso_balanza / tablaAuxiliar.cantidad as avg_peso_unitario')
            )
            ->orderBy('tablaAuxiliar.anio')
            ->orderBy('tablaAuxiliar.mes')
            ->get();

        return $resultados->map(function ($item) {
            $item->cantidad = (int) $item->cantidad;
            $item->avg_peso_unitario = (float) $item->avg_peso_unitario;
            return $item;
        });
        
    }

    public static function filtrar($filtro, $contenido_filtro){

        if(empty(Producto::FILTROS[$filtro])){
            return null;
        }

        $columna = Producto::FILTROS[$filtro];
        $consulta = Producto::where('activo', Producto::ACTIVO);

        switch ($columna) {
            case 'cod_prod':
                $consulta = $consulta->where($columna, 'like', $contenido_filtro.'%');
            break;
            default:
                $consulta = $consulta->where($columna, 'like', '%'.$contenido_filtro.'%');
        }

        return $consulta;
    }

    public static function obtener(){
        return Producto::where('activo', Producto::ACTIVO)->get();
    }

    public static function join_detalles_remitos($query_detalles_remitos)
    {
        return $query_detalles_remitos->join('productos', 'productos.id', '=', 'detalles_remitos.id_producto')
            ->where('productos.activo', Producto::ACTIVO);
    }
}