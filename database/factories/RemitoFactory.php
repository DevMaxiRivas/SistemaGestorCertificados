<?php

namespace Database\Factories;

use App\Models\Proveedor;
use App\Models\Remito;
use App\Models\PuntoVenta;
use App\Models\PuntoVentaProveedor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

class RemitoFactory extends Factory
{
    protected $model = Remito::class;

    public function definition()
    {
        $fecha = $this->faker->dateTimeBetween('-12 month', '-1 week');;

        $nro_random = random_int(1, 4);
        $random_fecha_recepcion = '+' . $nro_random  . ' day';
        $random_fecha_registro = '+' . random_int($nro_random, $nro_random + 3) . ' day';

        $pto_venta_prov = PuntoVentaProveedor::inRandomOrder()->first();

        return [
            'id_proveedor' => $pto_venta_prov->id_proveedor,
            'id_pto_venta_prov' => $pto_venta_prov->id,
            'nro_remito' => $this->faker->unique()->numberBetween(0, 9999),
            'id_pto_venta' => PuntoVenta::inRandomOrder()->first()->id,
            'nro_orden_compra' => $this->faker->unique()->numberBetween(0, 9999),
            'url_remito' => 'private/remitos/remito.pdf',
            'fecha_emision' => $fecha->format('Y-m-d'),
            'fecha_recepcion' => (clone $fecha)->modify($random_fecha_recepcion)->format('Y-m-d'),
            'fecha_registro' => (clone $fecha)->modify($random_fecha_registro),
            'observaciones' => $this->faker->sentence,
            'estado' => Remito::CONTROL_APROBADO,
            'created_at' => $this->faker->dateTimeThisYear,
            'updated_at' => Carbon::parse($this->faker->dateTimeThisYear)->addDays($this->faker->numberBetween(0, 10))
        ];
    }
}
