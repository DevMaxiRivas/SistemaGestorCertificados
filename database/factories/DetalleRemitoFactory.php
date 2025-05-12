<?php

namespace Database\Factories;

use App\Models\DetalleRemito;
use App\Models\Remito;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

class DetalleRemitoFactory extends Factory
{
    protected $model = DetalleRemito::class;

    public function definition()
    {
        $remito = Remito::inRandomOrder()->first();
        $detalles_remito = $remito->detalles_remito->pluck('id_producto');
        $id_producto = Producto::inRandomOrder()->first()->id;

        while($detalles_remito->contains($id_producto)){
            $id_producto = Producto::inRandomOrder()->first()->id;
        }

        return [
            'id_remito' => $remito->id,
            'id_producto' => $id_producto,
            'cantidad' => $this->faker->numberBetween(1, 10),
            'peso' => $this->faker->randomFloat(2, 100, 5000),
        ];
    }
}
