<?php

namespace Database\Seeders;

use App\Models\Provincia;
use App\Models\PuntoVenta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PuntoVentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PuntoVenta::create([
            'id_provincia' => Provincia::where('nombre', 'Salta')->first()->id,
            'nro_pto_venta' => 15,
            'sucursal' => 'CASA CENTRAL',
            'direccion' => 'AV. Paraguay 1450',
        ]);
        PuntoVenta::create([
            'id_provincia' => Provincia::where('nombre', 'Salta')->first()->id,
            'nro_pto_venta' => 16,
            'sucursal' => 'SUC CALLE SAN JUAN',
            'direccion' => 'San Juan 1344, Salta',
        ]);
        PuntoVenta::create([
            'id_provincia' => Provincia::where('nombre', 'Jujuy')->first()->id,
            'nro_pto_venta' => 21,
            'sucursal' => 'SUC JUJUY',
            'direccion' => 'Av. Corrientes 3390, San Salvador de Jujuy',
        ]);
        PuntoVenta::create([
            'id_provincia' => Provincia::where('nombre', 'Tucumán')->first()->id,
            'nro_pto_venta' => 19,
            'sucursal' => ' CD SAN ANDRES',
            'direccion' => 'RP306 Km 16, San Andrés de Tucumán',

        ]);
        PuntoVenta::create([
            'id_provincia' => Provincia::where('nombre', 'Tucumán')->first()->id,
            'nro_pto_venta' => 20,
            'sucursal' => ' SUC TUCUMAN',
            'direccion' => 'Av. Colón 925, San Miguel de Tucumán',

        ]);
    }
}
