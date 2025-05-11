<?php

namespace App\Filament\Resources\PuntoVentaProveedorResource\Pages;

use App\Filament\Resources\PuntoVentaProveedorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPuntoVentaProveedors extends ListRecords
{
    protected static string $resource = PuntoVentaProveedorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
