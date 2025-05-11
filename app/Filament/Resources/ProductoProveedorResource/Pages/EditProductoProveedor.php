<?php

namespace App\Filament\Resources\ProductoProveedorResource\Pages;

use App\Filament\Resources\ProductoProveedorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductoProveedor extends EditRecord
{
    protected static string $resource = ProductoProveedorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
