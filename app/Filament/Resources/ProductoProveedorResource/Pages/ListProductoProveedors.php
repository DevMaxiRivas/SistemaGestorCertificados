<?php

namespace App\Filament\Resources\ProductoProveedorResource\Pages;

use App\Filament\Resources\ProductoProveedorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductoProveedors extends ListRecords
{
    protected static string $resource = ProductoProveedorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
