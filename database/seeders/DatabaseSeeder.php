<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use App\Models\PesoTeoricoProducto;
use App\Models\Proveedor;
use App\Models\PuntoVentaProveedor;
use App\Models\Producto;
use App\Models\ProductoProveedor;
use App\Models\Certificado;
use App\Models\User;
use App\Models\Remito;
use App\Models\DetalleRemito;
use App\Models\Lote;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeders
        $this->call(ProvinciaSeeder::class);
        $this->call(PuntoVentaSeeder::class);
        $this->call(ProveedorSeeder::class);
        $this->call(ProductoSeeder::class);

        PesoTeoricoProducto::factory(100)->create();
        Proveedor::factory(29)->create();
        PuntoVentaProveedor::factory(100)->create();

        // Factories para producto
        Producto::all()->each(function ($producto) {
            $proveedores_disponibles = Proveedor::pluck('id')->shuffle();
            $faker = Faker::create();

            for ($i = 1; $i <= rand(1, 10); $i++) {
                $proveedor_id = $proveedores_disponibles->pop();
                ProductoProveedor::factory()->create([
                    'id_prod_empresa' => $producto->id,
                    'id_proveedor' => $proveedor_id,
                    'cod_prod_prov' => Str::upper($faker->unique()->bothify('********')),
                    'descripcion' => $faker->sentence(),
                    'activo' => ProductoProveedor::ACTIVO,
                ]);
            }
        });

        Remito::factory(5000)->create();

        $faker = Faker::create();

        Remito::all()->each(function ($remito) use ($faker) {
            $productos_disponibles = Producto::pluck('id')->shuffle();

            for ($i = 1; $i <= rand(1, $productos_disponibles->count()); $i++) {
                $producto_id = $productos_disponibles->pop();
                $peso_unitario = Producto::find($producto_id)->peso_unitario;
                $cantidad = rand(100, 1000);

                $detalle = DetalleRemito::factory()->create([
                    'id_remito' => $remito->id,
                    'id_producto' => $producto_id,
                    'cantidad' => $cantidad,
                    'peso' => ($peso_unitario * $cantidad) * (1 + (rand(0, 1) * 2 - 1) * 0.01 * rand(0, 10)),
                    'created_at' => $remito->fecha_registro,
                    'updated_at' => $remito->fecha_registro
                ]);

                $es_producto_con_certificado = rand(0, 1) == 1;

                $cant_lotes = rand(1, 10);
                for ($j = 1; $j <= $cant_lotes; $j++) {

                    $crear_nuevo_certificado = rand(0, 1) == 1;

                    if ($es_producto_con_certificado && $crear_nuevo_certificado) {
                        $certificado = Certificado::factory()->create([
                            'id_remito' => $remito->id,
                            'url_certificado' => 'private/certificados/certificado.pdf',
                            'created_at' => $remito->created_at,
                            'updated_at' => $remito->created_at
                        ]);
                    } else {
                        $certificado = $detalle->certificados()->inRandomOrder()->first();
                        if (is_null($certificado)) {
                            $certificado = Certificado::factory()->create([
                                'id_remito' => $remito->id,
                                'url_certificado' => 'private/certificados/certificado.pdf',
                                'created_at' => $remito->fecha_registro,
                                'updated_at' => $remito->fecha_registro,
                            ]);
                        }
                    }

                    $fecha_created_lote = Carbon::parse($remito->fecha_registro)->addMinutes(rand(0, 120));

                    Lote::factory()->create([
                        'id_det_remito' => $detalle->id,
                        'nro_lote' => Str::upper($faker->unique()->bothify('********')),
                        'peso' => ($detalle->peso - ($detalle->peso * 0.01 * rand(1, 10))) / $cant_lotes,
                        'id_certificado' => $certificado->id,
                        'created_at' => $fecha_created_lote,
                        'updated_at' => $fecha_created_lote->addMinutes(rand(0, 120)),
                    ]);
                }
            }
        });
    }
}
