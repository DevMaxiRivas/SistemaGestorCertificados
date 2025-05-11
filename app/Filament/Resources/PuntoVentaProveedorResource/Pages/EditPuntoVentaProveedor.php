<?php

namespace App\Filament\Resources\PuntoVentaProveedorResource\Pages;

use App\Filament\Resources\PuntoVentaProveedorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPuntoVentaProveedor extends EditRecord
{
    protected static string $resource = PuntoVentaProveedorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
