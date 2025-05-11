<?php

namespace App\Models;

// Auditoria
use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

class Proveedor extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    // Estados del proveedor
    const ACTIVO = '1';
    const INACTIVO = '0';

    const funciones_DB_equivalentes = [
        'diferencia_fechas_timestamp' => [
            'sqlsrv' => 'DATEDIFF',
            'mysql' => 'TIMESTAMPDIFF'
        ]
    ];

    // Nombre de la tabla que se conecta a este Modelo
    protected $table = 'proveedores';

    // Nombres de las columnas que son modificables
    protected $fillable = [
        'razon_social',
        'cuit', 
        'direccion', 
        'telefono',
        'email', 
        'activo'
    ];

    public function eliminar()
    {
        ProductoProveedor::eliminarPorProveedor($this);
        $this->activo = Proveedor::INACTIVO;
        $this->update();
    }

    public static function obtener()
    {
        return Proveedor::where('activo', Proveedor::ACTIVO)->get();
    }

    public function puntos_venta()
    {
        return $this->hasMany(PuntoVentaProveedor::class, 'id_proveedor');
    }

    // public function productos()
    // {
    //     return $this->hasMany(ProductoProveedor::class, 'id_proveedor');
    // }

    public function productos(){
        return $this->belongsToMany(Producto::class, 'productos_por_proveedor', 'id_proveedor', 'id_prod_empresa')
            ->where('productos_por_proveedor.activo', ProductoProveedor::ACTIVO)
            ->withPivot('activo');
    }

    // public function existe_producto_registrado($cod_prod = null, $cod_prod_prov = null)
    // {
    //     return ProductoProveedor::existe(cod_prod: $cod_prod, cod_prod_prov: $cod_prod_prov, proveedor: $this);
    // }

    public function productos_sin_registrar()
    {
        return Producto::whereNotIn('productos.id', $this->productos()->pluck('productos.id'));
    }

    public function filtrar_productos_sin_registrar($contenido_filtro, $filtro)
    {
        return Producto::filtrar($filtro, $contenido_filtro)
            ->whereNotIn('productos.id', $this->productos->pluck('id'));
    }

    public static function join_remitos($query_remitos)
    {
        return $query_remitos->join('proveedores', 'proveedores.id', '=', 'remitos.id_proveedor')
        ->where('proveedores.activo', Proveedor::ACTIVO);
    }

    public static function buscarProveedores($texto_busqueda) {

        return Proveedor::where('activo', Proveedor::ACTIVO)
        ->where('razon_social', 'like', $texto_busqueda . '%')
        ->orderBy('razon_social');
    }

    public static function obtener_productos_sin_registrar($proveedor = null)
    {
        $query = DB::table('remitos')
            ->join('detalles_remitos', 'remitos.id', '=', 'detalles_remitos.id_remito')
            ->join('productos', 'productos.id', '=', 'detalles_remitos.id_producto')
            ->leftJoin('productos_por_proveedor', function ($join) {
                $join->on('detalles_remitos.id_producto', '=', 'productos_por_proveedor.id_prod_empresa')
                    ->on('productos_por_proveedor.id_proveedor', '=', 'remitos.id_proveedor');
            })
            ->whereNull('productos_por_proveedor.descripcion');

        if (!is_null($proveedor)) {
            $query->where('remitos.id_proveedor', $proveedor->id);
        }

        return $query
            ->distinct()
            ->select('productos.cod_prod','productos.descripcion', 'detalles_remitos.id_producto', 'remitos.id_proveedor');
    }

    public static function promedio_dias_de_envio_por_proveedor($cantidad_registros = 3)
    {
        $funcion_diferencia_db = self::funciones_DB_equivalentes['diferencia_fechas_timestamp'][Config::get('database.default')];
        return DB::table('remitos')
            ->join('proveedores', 'proveedores.id', '=', 'remitos.id_proveedor')
            ->groupBy('remitos.id_proveedor', 'proveedores.razon_social')
            ->selectRaw('
                proveedores.razon_social,
                AVG('.$funcion_diferencia_db.'(DAY, remitos.fecha_emision, CAST(remitos.fecha_registro AS DATE))) as cant_dias_promedio
            ')
            ->orderBy('cant_dias_promedio', 'asc')
            ->limit($cantidad_registros)
            ->get();
    }
}