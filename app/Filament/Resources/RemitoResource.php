<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RemitoResource\Pages;
use App\Filament\Resources\RemitoResource\RelationManagers;
use App\Filament\Resources\RemitoResource\RelationManagers\DetallesRemitoRelationManager;
use App\Models\Remito;
use App\Models\Proveedor;
use App\Models\PuntoVenta;
use App\Models\PuntoVentaProveedor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
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
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RemitoResource extends Resource
{
    protected static ?string $model = Remito::class;
    // Atributos de la tabla
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Pedidos';
    protected static ?string $navigationLabel = 'Remitos';
    protected static ?string $label = 'Remito';
    protected static ?string $pluralLabel = 'Remitos';
    protected static ?string $slug = 'remitos';

    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Remito';
    protected static ?string $modelPluralLabel = 'Remitos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns([
                        'sm' => 1,
                        'xl' => 2,
                        '2xl' => 5,
                    ])
                    ->schema([
                        Select::make('id_proveedor')
                            ->relationship('proveedor', 'razon_social')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            ->afterStateUpdated(
                                fn(Get $get, callable $set) => $set('id_pto_venta_prov', null)
                            )
                            ->columnSpan([
                                'sm' => 5,
                                'xl' => 2,
                                '2xl' => 2,
                            ])
                            ->label('Proveedor'),
                        Select::make('id_pto_venta_prov')
                            ->options(
                                fn(Get $get): Collection => PuntoVentaProveedor::where('id_proveedor', $get('id_proveedor'))
                                    ->pluck('nro_pto_venta', 'id')
                            )
                            ->preload()
                            ->live()
                            ->required()
                            ->columnSpan([
                                'sm' => 5,
                                'xl' => 1,
                                '2xl' => 1,
                            ])
                            ->label('Punto de venta proveedor'),
                        TextInput::make('nro_remito')
                            ->required()
                            ->columnSpan([
                                'sm' => 5,
                                'xl' => 2,
                                '2xl' => 2,
                            ])
                            ->label('Número de remito'),
                        DatePicker::make('fecha_emision')
                            ->required()
                            ->label('Fecha de emisión'),
                        DatePicker::make('fecha_recepcion')
                            ->required()
                            ->label('Fecha de recepción'),
                        Select::make('id_pto_venta')
                            ->relationship('punto_venta', 'nro_pto_venta')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Punto de venta'),
                        TextInput::make('nro_orden_compra')
                            ->required()
                            ->columnSpan([
                                'sm' => 5,
                                'xl' => 1,
                                '2xl' => 2,
                            ])
                            ->label('Número de orden de compra'),
                        Textarea::make('observaciones')
                            ->label('Observaciones')
                            ->columnSpan('full'),
                        FileUpload::make('url_remito')
                            ->label('Remito')
                            ->preserveFilenames()
                            ->columnSpan('full')
                            ->directory('remitos')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(1024),
                        FileUpload::make('url_remito')
                            ->label('Certificados')
                            ->preserveFilenames()
                            ->columnSpan('full')
                            ->directory('certificados')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(1024),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('proveedor.razon_social')
                    ->label('Proveedor')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('punto_venta_proveedor.nro_pto_venta')
                    ->label('Punto de venta proveedor')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nro_remito')
                    ->label('Número de remito')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('punto_venta.nro_pto_venta')
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
                    ->date(),
                TextColumn::make('fecha_recepcion')
                    ->label('Fecha de recepción')
                    ->sortable()
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\Action::make('Ver PDF')
                    ->url(fn(Remito $record): string => $record->url_remito)
                    ->icon('heroicon-o-eye')
                    ->openUrlInNewTab()
                    ->label('PDF'),
                Tables\Actions\Action::make('Ver Certificado')
                    ->url("/")
                    ->icon('heroicon-o-eye')
                    ->openUrlInNewTab()
                    ->label('Certificado'),
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
            DetallesRemitoRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRemitos::route('/'),
            'create' => Pages\CreateRemito::route('/crear'),
            'edit' => Pages\EditRemito::route('/{record}/editar'),
        ];
    }
}