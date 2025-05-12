<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\Producto;
use App\Models\PesoTeoricoProducto;
use Illuminate\Database\Eloquent\Factories\Factory;

class PesoTeoricoProductoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PesoTeoricoProducto::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $producto = Producto::inRandomOrder()->first();
        return [
            'id_producto' => $producto->id,
            'peso_teorico' => ($producto->peso_unitario) * (1 + (rand(0,1)*2-1) * 0.01 * rand(0,10)),
            'fecha_modificacion' => $this->faker->dateTimeBetween('-2 year', 'now')->format('Y-m-d'),
        ];
        
    }
}
