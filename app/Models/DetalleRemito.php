<?php

namespace App\Models;

// Auditoria
use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class DetalleRemito extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    
    // Constantes de estado de la devolucions
    const ACTIVO = '1';
    const INACTIVO = '0';

    // Nombre de la tabla que se conecta a este Modelo
    protected $table = 'detalles_remitos';

    // Nombres de las columnas que son modificables
    protected $fillable = [
        'id_remito',
        'id_producto',
        'peso',
        'cantidad',
        'activo'
    ];

    public static function obtener(Remito $remito)
    {
        return DetalleRemito::where('detalles_remitos.activo', DetalleRemito::ACTIVO)
            ->where('detalles_remitos.id_remito', $remito->id)
            ->get();
    }

    public function es_eliminable()
    {
        return $this->lotes()->count() == 0 && $this->peso == null && $this->cantidad == null;
    }

    public function eliminar()
    {
        if(!$this->es_eliminable()) {
            return false;
        }

        $this->activo = DetalleRemito::INACTIVO;
        $this->update();
        return true;
    }

    public function remito()
    {
        return $this->belongsTo(Remito::class, 'id_remito');
    }

    public function punto_venta()
    {
        return $this->remito->punto_venta;
    }

    public function lotes()
    {
        return $this->hasMany(Lote::class, 'id_det_remito')->where('lotes.activo', Lote::ACTIVO);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function peso_total_lotes()
    {
        return $this->lotes->sum('peso');
    }

    public function variacion_pesos()
    {
        return $this->peso ? ($this->peso - $this->peso_total_lotes())/$this->peso : 0;
    }

    // Nunca se eliminan los certificados en pdf del servidor
    public function eliminarLotes() {
        $lotes = $this->lotes;
        foreach ($lotes as $lote) {
            $lote->eliminar();
        }
    }

    public function eliminarDetalleRemito() {
        $this->eliminarLotes();
        $this->eliminar();
    }

    public static function guardarMultiplesProductos($productos, Remito $remito) 
    {
        foreach ($productos as $producto) {
            DetalleRemito::create([
                'id_remito' => $remito->id,
                'id_producto' => $producto['id'],
            ]);
        }
    }

    public static function actualizarMultiplesDetalles($detalles)
    {
        if(!empty($detalles)){
            foreach ($detalles as $detalle) {
                $det = DetalleRemito::find($detalle['detalle']);
                $det->update([
                    'cantidad' => $detalle['cantidad'] ? $detalle['cantidad'] : $det->cantidad,
                    'peso' => $detalle['peso'] ? $detalle['peso'] : $det->peso
                ]);
            }
        }
    }

    public static function editarMultiplesDetalles($detalles) 
    {
        if(!empty($detalles)){
            foreach ($detalles as $detalle) {
                DetalleRemito::find($detalle['id'])->update([
                    'cantidad' => $detalle['cantidad'],
                    'peso' => $detalle['peso'],
                ]);
            }
        }
    }
    
    public static function eliminarPorRemito(Remito $remito)
    {
        $detalles = $remito->detalles_remito;
        if(!empty($detalles)){
            foreach( $detalles as $detalle){
                $detalle->eliminar();
            }
        }
    }

    public static function join_remitos(
            $query_remitos,
            $productos = null,
            $peso = null,
            $cantidad = null,
        ) {
        $query_remitos = $query_remitos->join('detalles_remitos', 'detalles_remitos.id_remito', '=', 'remitos.id')
            ->where('detalles_remitos.activo', DetalleRemito::ACTIVO);

        if(!empty($productos)) $query_remitos = $query_remitos->whereIn('detalles_remitos.id_producto', $productos);

        return $query_remitos;

    }

    public function certificados()
    {
        return $this->belongsToMany(Certificado::class, 'lotes', 'id_det_remito', 'id_certificado')
            ->where('lotes.activo', Lote::ACTIVO)
            ->withPivot('activo');
    }
}