<?php

namespace App\Filament\Resources\PuntoVentaResource\Pages;

use App\Filament\Resources\PuntoVentaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPuntoVentas extends ListRecords
{
    protected static string $resource = PuntoVentaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
