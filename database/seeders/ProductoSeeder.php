<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Producto::create([
            'cod_prod' => 'ALINOXMlG09308L',
            'descripcion' => 'Hierro Torsionado 6 Mm Barra 12 Metros',
            'descripcion_detallada' => 'Son aceros al carbono destinados a la construcción, elaborados en hornos eléctricos Sus características mecánicas están dadas por el manejo de la composición química y por procesos de laminado con equipos de alta tecnología. Aplicación: En armaduras en cualquier estructura de hormigón armado que no requiera características de soldabilidad. Hierro torsionado de 6mm por 12mts de largo para construcción. Aplicable para armaduras en cualquier estructura de hormigón armado que no requiera características de soldabilidad.',
            'peso_unitario' => 2.5,
        ]);
        
        Producto::create([
            'cod_prod' => 'ALAR1614',
            'descripcion' => 'Chapa Acanalada Cincalum Nº 27 1.10 X 3.5 Mt',
            'descripcion_detallada' => 'La chapa cincalum acanalada brinda una excelente resistencia a la corrosión y a las altas temperaturas, lo que le permite superar ampliamente la vida útil del galvanizado. Chapa de acero, de 1,10m de ancho,revestida por el proceso de inmersión en caliente con una aleación de aluminio y cinc en ambas caras. Es ideal para la fabricación de cerramientos, cubiertas residenciales, comerciales o industriales.',
            'peso_unitario' => 10,
        ]);
        
        Producto::create([
            'cod_prod' => 'ALH115030',
            'descripcion' => 'Perfil C Negro Ternium 80X40X15X2',
            'descripcion_detallada' => 'Perfil sección "C" fabricado en acero laminado en caliente o galvanizado que garantiza un alto grado de durabilidad y resistencia a la intemperie. De amplio uso en industria de la construcción, ofrece flexibilidad y rapidez en la construcción de estructuras metálicas. Para uso en aberturas, pilares de soporte, travesaños y otros elementos de conformación de estructuras. Asimismo, tiene aplicaciones en otras industrias, como el agro y el transporte.',
            'peso_unitario' => 4.12,
        ]);
        
        Producto::create([
            'cod_prod' => 'PC502516',
            'descripcion' => 'PERFIL "C"  50 X 25 X 10 X 1,6 X 6MTS LARGO',
            'descripcion_detallada' => 'PERFIL "C"  50 X 25 X 10 X 1,6 X 6MTS LARGO',
            'peso_unitario' => 9
        ]);
        
        Producto::create([
            'cod_prod' => 'PC603016',
            'descripcion' => 'PERFIL "C"  60 X 30 X 10 X 1,6 X 6MTS LARGO',
            'descripcion_detallada' => 'PERFIL "C"  60 X 30 X 10 X 1,6 X 6MTS LARGO',
            'peso_unitario' => 10.3
        ]);
        
        Producto::create([
            'cod_prod' => 'PC80401516',
            'descripcion' => 'PERFIL "C"  80 X 50 X 15 X 1,6 X METRO',
            'descripcion_detallada' => 'PERFIL "C"  80 X 50 X 15 X 1,6 X METRO',
            'peso_unitario' => 2.25
        ]);
        
        Producto::create([
            'cod_prod' => 'PC80501516',
            'descripcion' => 'PERFIL "C"  80 X 50 X 15 X 1,6 X METRO',
            'descripcion_detallada' => 'PERFIL "C"  80 X 50 X 15 X 1,6 X METRO',
            'peso_unitario' => 2.48
        ]);
        
        Producto::create([
            'cod_prod' => 'PC8050152',
            'descripcion' => 'PERFIL "C"  80 X 50 X 15 X 2 X METRO',
            'descripcion_detallada' => 'PERFIL "C"  80 X 50 X 15 X 2 X METRO',
            'peso_unitario' => 3.1 
        ]);
        
        Producto::create([
            'cod_prod' => 'PC100401516',
            'descripcion' => 'PERFIL "C" 100 X 40 X 15 X 1,6 X METRO',
            'descripcion_detallada' => 'PERFIL "C" 100 X 40 X 15 X 1,6 X METRO',
            'peso_unitario' => 2.5
        ]);
    }
}
