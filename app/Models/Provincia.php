<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Provincia extends Model
{
    use HasFactory;
    
    // Constantes de estado de la devolucions
    const ACTIVO = '1';
    const INACTIVO = '0';

    // Nombre de la tabla que se conecta a este Modelo
    protected $table = 'provincias';

    // Nombres de las columnas que son modificables
    protected $fillable = [
        'nombre',
    ];

    public function eliminar()
    {
        return $this->activo = Provincia::INACTIVO;
    }

    public static function obtener(){
        return Provincia::get();
    }
}
