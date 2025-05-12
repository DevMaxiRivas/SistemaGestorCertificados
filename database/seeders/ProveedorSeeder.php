<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Proveedor::create([
            'razon_social' => 'Sidersa',
            'cuit' => '30615368292',
            'direccion' => 'Ruta Nacional Nro.9 Km 226,5 2900',
            'telefono' => '5490233232',
            'email' => 'contacto@sidersa.com',
            'activo' => Proveedor::ACTIVO
        ]);
    }
}
