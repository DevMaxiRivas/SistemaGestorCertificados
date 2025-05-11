<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PuntoVentaResource\Pages;
use App\Filament\Resources\PuntoVentaResource\RelationManagers;
use App\Models\PuntoVenta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PuntoVentaResource extends Resource
{
    protected static ?string $model = PuntoVenta::class;
    // Atributos
    // 'id_provincia',
    // 'nro_pto_venta',
    // 'sucursal',
    // 'direccion',

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Empresa';
    protected static ?string $label = 'Punto de Venta';
    protected static ?string $pluralLabel = 'Puntos de Venta';
    protected static ?string $slug = 'puntos-venta';
    protected static ?string $navigationLabel = 'Puntos de Venta';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Punto de Venta';
    protected static ?string $pluralModelLabel = 'Puntos de Venta';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns([
                        'sm' => 1,
                        'xl' => 2,
                        '2xl' => 4,
                    ])
                    ->schema([
                        Select::make('id_provincia')
                            ->label('Provincia')
                            ->relationship('provincia', 'nombre')
                            ->required()
                            ->preload()
                            ->reactive(),
                        TextInput::make('nro_pto_venta')
                            ->required()
                            ->numeric()
                            ->maxLength(255),
                        TextInput::make('sucursal')
                            ->required()
                            ->maxLength(255),
                        Select::make('activo')
                            ->label('Estado')
                            ->options([
                                PuntoVenta::ACTIVO => 'Activo',
                                PuntoVenta::INACTIVO => 'Inactivo',
                            ])
                            ->default(PuntoVenta::ACTIVO)
                            ->required()
                            ->reactive(),
                        TextInput::make('direccion')
                            ->required()
                            ->columnSpan('full')
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('provincia.nombre')
                    ->label('Provincia')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nro_pto_venta')
                    ->label('Número de punto de venta')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sucursal')
                    ->label('Sucursal')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('direccion')
                    ->label('Dirección')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('activo')
                    ->label('Estado')
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
            'index' => Pages\ListPuntoVentas::route('/'),
            'create' => Pages\CreatePuntoVenta::route('/crear'),
            'edit' => Pages\EditPuntoVenta::route('/{record}/editar'),
        ];
    }
}
