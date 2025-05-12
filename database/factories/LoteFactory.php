<?php

namespace Database\Factories;

use App\Models\Lote;
use App\Models\DetalleRemito;
use App\Models\Certificado;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LoteFactory extends Factory
{
    protected $model = Lote::class;

    public function definition()
    {

        $detalle_remito = DetalleRemito::inRandomOrder()->first();
        $certificado = Certificado::where('id_remito', $detalle_remito->id_remito)->inRandomOrder()->first();
        return [
            'id_det_remito' => $detalle_remito->id,
            'nro_lote' => Str::upper($this->faker->unique()->bothify('********')),
            'peso' => $this->faker->randomFloat(2, 100, 5000),
            'id_certificado' => !is_null($certificado) ? $certificado->id : null,
        ];
    }
}
