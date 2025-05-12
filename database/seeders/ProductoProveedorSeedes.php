<?php

namespace Database\Seeders;

use App\Models\ProductoProveedor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductoProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductoProveedor::create([
            'id_proveedor' => intval(rand(1, 10)),
            'id_producto' => intval(rand(1, 10)),
            'cod_prod' => 'alkmdwalkmdw',
            'descripcion' => 'Hierro Torsionado 6 Mm Barra 12 Metros',
        ]);
        
        ProductoProveedor::create([
            'id_proveedor' => intval(rand(1, 10)),
            'id_producto' => intval(rand(1, 10)),
            'cod_prod' => 'awodpkdopa',
            'descripcion' => 'Chapa Acanalada Cincalum Nº 27 1.10 X 3.5 Mt',
        ]);
        
        ProductoProveedor::create([
            'id_proveedor' => intval(rand(1, 10)),
            'id_producto' => intval(rand(1, 10)),
            'cod_prod' => 'aowpdapkd',
            'descripcion' => 'Perfil C Negro Ternium 80X40X15X2',
        ]);
        ProductoProveedor::create([
            'id_proveedor' => intval(rand(1, 10)),
            'id_producto' => intval(rand(1, 10)),
            'cod_prod' => '10478',
            'descripcion' => 'Tubo 2"(50.8)x1.60 LAF',
        ]);
    }
}
