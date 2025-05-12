<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ProductoProveedor;
use App\Models\Proveedor;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductoProveedor>
 */
class ProductoProveedorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_prod_empresa' => Producto::inRandomOrder()->first()->id,
            'id_proveedor' => Proveedor::inRandomOrder()->first()->id,
            'cod_prod_prov' => Str::upper($this->faker->unique()->bothify('********')), // Genera un código de artículo único con números y letras
            'descripcion' => $this->faker->sentence(),
            'activo' => ProductoProveedor::ACTIVO,
        ];
    }
}
