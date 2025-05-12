<?php
// database/factories/UserFactory.php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'rol' => $this->faker->randomElement([
                User::ADMINISTRADOR, 
                User::GERENTE,
                User::ENCARGADO_COMERCIAL,
                User::ENCARGADO_REMITO,
                User::PERSONAL_DEPOSITO,
                User::EMPLEADO_SIN_ROL
            ]),
            'name' => $this->faker->name(),
            'cuit' => $this->faker->numerify('###########'), // Genera un número de 11 dígitos
            'telefono' => $this->faker->optional()->phoneNumber(),
            'estado' => $this->faker->randomElement([User::BAJA, User::ALTA]),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'created_at' => $this->faker->dateTimeThisYear,
            'updated_at' => Carbon::parse($this->faker->dateTimeThisYear)->addDays($this->faker->numberBetween(0, 10))
        ];
    }
}
