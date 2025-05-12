<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Provincia;
use App\Models\Proveedor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PuntoVentaProveedor>
 */
class PuntoVentaProveedorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_provincia' => Provincia::inRandomOrder()->first()->id,
            'id_proveedor' => Proveedor::inRandomOrder()->first()->id,
            'nro_pto_venta' => $this->faker->unique()->numerify('##'),
            'sucursal' => 'Sucursal',
            'direccion' => $this->faker->address(),
        ];
    }
}
