<?php

namespace App\Filament\Resources\PuntoVentaResource\Pages;

use App\Filament\Resources\PuntoVentaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPuntoVenta extends EditRecord
{
    protected static string $resource = PuntoVentaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
