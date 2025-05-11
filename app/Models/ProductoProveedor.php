<?php

namespace App\Models;

// Auditoria
use OwenIt\Auditing\Contracts\Auditable;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Log;

class ProductoProveedor extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    // Estados del producto
    const ACTIVO = '1';
    const INACTIVO = '0';

    // Nombre de la tabla que se conecta a este Modelo
    protected $table = 'productos_por_proveedor';

    // Nombres de las columnas que son modificables
    protected $fillable = [
        'id_prod_empresa',
        'id_proveedor',
        'cod_prod_prov',
        'descripcion',
        'activo'
    ];

    public function eliminar()
    {
        $this->activo = ProductoProveedor::INACTIVO;
        $this->update();
    }

    public static function eliminarPorProveedor(Proveedor $proveedor)
    {
        return ProductoProveedor::where('activo', ProductoProveedor::ACTIVO)
            ->where('id_proveedor', $proveedor->id)
            ->update(['activo' => ProductoProveedor::INACTIVO]);
    }

    // Solo utilizar con una de las colimnas
    public static function existe(
        $producto = null,
        $cod_prod_prov = null, 
        $proveedor
    ){
        $query = ProductoProveedor::where('id_proveedor', $proveedor->id)->where('activo', ProductoProveedor::ACTIVO);
        
        if(!empty($producto)){
            $query = $query->where('id_prod_empresa', $producto->id);
        }

        if (!empty($cod_prod_prov)) {
            $query = $query->where('cod_prod_prov', $cod_prod_prov);
        }

        Log::info($query->toSql());
        Log::info($query->getBindings());

        return $query->first();

    }


    public static function obtener()
    {
        return ProductoProveedor::where('activo', ProductoProveedor::ACTIVO)->get();
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_prod_empresa');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    public static function productoPorProveedor(Proveedor $proveedor)
    {
        return ProductoProveedor::where('activo', ProductoProveedor::ACTIVO)
            ->where('id_proveedor', $proveedor->id)
            ->get();
    }
}