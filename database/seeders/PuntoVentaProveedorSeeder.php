<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use App\Models\PuntoVentaProveedor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PuntoVentaProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Proveedor::all()->each(function ($proveedor) {
            PuntoVentaProveedor::create([
                'id_provincia' => Province::inRandomOrder()->first()->id,
                'id_proveedor' => $proveedor->id,
                'nro_pto_venta' => 15,
                'sucursal' => 'Sucursal',
                'direccion' => 'Direccion',
            ]);
        });
    }
}
