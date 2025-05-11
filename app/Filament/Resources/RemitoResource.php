<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RemitoResource\Pages;
use App\Filament\Resources\RemitoResource\RelationManagers;
use App\Models\Remito;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;

use Filament\Tables\Columns\TextColumn;


use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RemitoResource extends Resource
{
    protected static ?string $model = Remito::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        // Campos de la tabla
        // 'id_proveedor',
        // 'id_pto_venta_prov',
        // 'nro_remito',
        // 'id_pto_venta',
        // 'nro_orden_compra',
        // 'fecha_emision',
        // 'fecha_recepcion',
        // 'url_remito',
        // 'observaciones',
        // 'id_empleado',
        // 'estado',
        // 'activo',

        return $form
            ->schema([
                Section::make()
                    ->columns([
                        'sm' => 1,
                        'xl' => 2,
                        '2xl' => 3,
                    ])
                    ->schema([
                        Select::make('id_proveedor')
                            // ->relationship('proveedores', 'razon_social')
                            ->options([
                                '1' => 'Proveedor 1',
                                '2' => 'Proveedor 2',
                                '3' => 'Proveedor 3',
                            ])
                            ->required()
                            ->label('Proveedor'),
                        Select::make('id_pto_venta_prov')
                            // ->relationship('puntos_venta_proveedores', 'nro_pto_venta')
                            ->options([
                                '1' => 'PV 1',
                                '2' => 'PV 2',
                                '3' => 'PV 3',
                            ])
                            ->required()
                            ->label('Punto de venta proveedor'),
                        TextInput::make('nro_remito')
                            ->required()
                            ->label('Número de remito'),
                        Select::make('id_pto_venta')
                            // ->relationship('punto_venta', 'nro_pto_venta')
                            ->options([
                                '1' => 'PV 1',
                                '2' => 'PV 2',
                                '3' => 'PV 3',
                            ])
                            ->required()
                            ->label('Punto de venta'),
                        TextInput::make('nro_orden_compra')
                            ->required()
                            ->label('Número de orden de compra'),
                        DatePicker::make('fecha_emision')
                            ->required()
                            ->label('Fecha de emisión'),
                        DatePicker::make('fecha_recepcion')
                            ->required()
                            ->label('Fecha de recepción'),
                        FileUpload::make('url_remito')
                            ->label('Remito')
                            ->required()
                            ->preserveFilenames()
                            ->directory('remitos')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(1024),
                        Textarea::make('observaciones')
                            ->label('Observaciones')
                            ->columnSpan([
                                'sm' => 2,
                            ]),
                        // Select::make('id_empleado')
                        //     ->relationship('empleado', 'name')
                        //     ->required()
                        //     ->label('Empleado'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_proveedor')
                    ->label('Proveedor')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('id_pto_venta_prov')
                    ->label('Punto de venta proveedor')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nro_remito')
                    ->label('Número de remito')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('id_pto_venta')
                    ->label('Punto de venta')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nro_orden_compra')
                    ->label('Número de orden de compra')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('fecha_emision')
                    ->label('Fecha de emisión')
                    ->sortable()
                    ->dateTime(),
                TextColumn::make('fecha_recepcion')
                    ->label('Fecha de recepción')
                    ->sortable()
                    ->dateTime(),
                TextColumn::make('url_remito')
                    ->label('Remito'),
                TextColumn::make('observaciones')
                    ->label('Observaciones'),
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
            'index' => Pages\ListRemitos::route('/'),
            'create' => Pages\CreateRemito::route('/create'),
            'edit' => Pages\EditRemito::route('/{record}/edit'),
        ];
    }
}
