<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Audit extends Model 
{
    use HasFactory;
    
    // Nombre de la tabla que se conecta a este Modelo
    protected $table = 'audits';

    const EVENTOS = [
        'created' => 'Creación',
        'updated' => 'Actualización',
        'deleted' => 'Eliminación',
        'restored' => 'Restauración',
        'forceDeleted' => 'Eliminación forzada',
    ];


    public static function obtener($cantidad = 100)
    {
        $audits = Audit::
            join('users', 'audits.user_id', '=', 'users.id')
            // ->orderBy('created_at', 'desc')
            ->take($cantidad)
            ->select(
                'audits.*',
                'users.name as user_name',
                'users.email as user_email'
            )->get();

        // Mapeamos la ruta del modelo
        $audits->map(function ($audit) {
            $audit->model = json_decode($audit->new_values);
            $audit->model_name = class_basename($audit->auditable_type);
            $audit->model_id = $audit->auditable_id;
            $audit->event = self::EVENTOS[$audit->event];
            return $audit;
        });

        return $audits;
    }

}
