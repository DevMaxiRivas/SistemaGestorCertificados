<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Producto::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id_empleado' => 1,
            'id_categoria' => $this->faker->randomElement([1, 2, 3, 4]), // Suponiendo que tienes categorías con IDs 1 a 5
            'cod_prod' => Str::upper($this->faker->unique()->bothify('********')), // Genera un código de artículo único con números y letras
            'descripcion' => $this->faker->sentence(),
            'descripcion_detallada' => $this->faker->paragraph(),
            'peso_unitario' => $this->faker->randomFloat(2, 1, 100),
            'activo' => Producto::ACTIVO,
        ];
    }
}
