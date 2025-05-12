<?php

namespace Database\Factories;

use App\Models\Certificado;
use App\Models\Remito;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CertificadoFactory extends Factory
{
    protected $model = Certificado::class;

    public function definition()
    {
        return [
            'id_remito' => Remito::inRandomOrder()->first()->id,
            'url_certificado' => 'private/certificados/certificado.pdf',
        ];
    }
}
