<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoProveedorResource\Pages;
use App\Filament\Resources\ProductoProveedorResource\RelationManagers;
use App\Models\ProductoProveedor;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductoProveedorResource extends Resource
{
    protected static ?string $model = ProductoProveedor::class;
    // Atributos de la tabla
    // 'id_prod_empresa',
    // 'id_proveedor',
    // 'cod_prod_prov',
    // 'descripcion',
    // 'activo'

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Proveedores';
    protected static ?string $navigationLabel = 'Productos';
    protected static ?string $label = 'Producto Proveedor';
    protected static ?string $pluralLabel = 'Productos Proveedores';
    protected static ?string $slug = 'productos-proveedores';
    protected static ?string $modelLabel = 'Producto Proveedor';
    protected static ?string $modelPluralLabel = 'Productos Proveedores';

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
                        Forms\Components\Select::make('id_prod_empresa')
                            ->label('Código de Producto')
                            ->relationship('producto', 'cod_prod')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->columnSpan([
                                'sm' => 4,
                                'xl' => 2,
                                '2xl' => 1,
                            ]),
                        Forms\Components\Select::make('id_proveedor')
                            ->label('Proveedor')
                            ->relationship('proveedor', 'razon_social')
                            ->searchable()
                            ->required()
                            ->preload(),
                        Forms\Components\TextInput::make('cod_prod_prov')
                            ->label('Código de Proveedor')
                            ->required(),
                        Forms\Components\TextInput::make('descripcion')
                            ->label('Descripción'),
                        Forms\Components\Toggle::make('activo')
                            ->label('Activo'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('producto.cod_prod')
                    ->label('Código de Producto')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('proveedor.razon_social')
                    ->label('Proveedor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cod_prod_prov')
                    ->label('Código de Proveedor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([])
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
            'index' => Pages\ListProductoProveedors::route('/'),
            'create' => Pages\CreateProductoProveedor::route('/create'),
            'edit' => Pages\EditProductoProveedor::route('/{record}/edit'),
        ];
    }
}
