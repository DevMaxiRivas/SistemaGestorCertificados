<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PuntoVentaProveedorResource\Pages;
use App\Filament\Resources\PuntoVentaProveedorResource\RelationManagers;
use App\Models\PuntoVentaProveedor;
use Filament\Forms;
use Filament\Forms\Form;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

use Filament\Tables\Columns\TextColumn;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PuntoVentaProveedorResource extends Resource
{
    protected static ?string $model = PuntoVentaProveedor::class;
    // Atributos de la tabla
    // 'id_provincia',
    // 'id_proveedor',
    // 'nro_pto_venta',
    // 'sucursal',
    // 'direccion',
    // 'activo',

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Proveedores';
    protected static ?string $navigationLabel = 'Puntos de Venta Proveedores';
    protected static ?string $label = 'Punto de Venta';
    protected static ?string $pluralLabel = 'Puntos de venta';
    protected static ?string $slug = 'puntos-venta-proveedores';
    protected static ?string $modelLabel = 'Punto de Venta de Proveedor';
    protected static ?string $modelPluralLabel = 'Puntos de Venta Proveedor';
    protected static ?string $recordTitleAttribute = 'sucursal';
    protected static ?string $title = 'Puntos de venta proveedores';
    protected static ?string $description = 'Puntos de venta de proveedores';
    protected static ?int $navigationSort = 2;
    protected static ?string $searchLabel = 'Buscar';
    protected static ?string $searchPlaceholder = 'Buscar por sucursal, dirección o provincia';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns([
                        'sm' => 1,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        Select::make('id_provincia')
                            ->relationship('provincias', 'nombre')
                            ->required()
                            ->label('Provincia'),
                        Select::make('id_proveedor')
                            ->relationship('proveedores', 'razon_social')
                            ->required()
                            ->label('Proveedor'),
                        TextInput::make('nro_pto_venta')
                            ->numeric()
                            ->required()
                            ->label('Número de punto de venta'),
                        TextInput::make('sucursal')
                            ->required()
                            ->label('Nombre de sucursal'),
                        TextInput::make('direccion')
                            ->required()
                            ->columnSpan('full')
                            ->maxLength(255)
                            ->label('Dirección'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('provincia.nombre')
                    ->label('Provincia')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('proveedor.razon_social')
                    ->label('Proveedores')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nro_pto_venta')
                    ->label('Número de punto de venta')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sucursal')
                    ->label('Sucursal')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('direccion')
                    ->label('Dirección')
                    ->searchable()
                    ->sortable(),
                // TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPuntoVentaProveedors::route('/'),
            'create' => Pages\CreatePuntoVentaProveedor::route('/create'),
            'edit' => Pages\EditPuntoVentaProveedor::route('/{record}/edit'),
        ];
    }
}
