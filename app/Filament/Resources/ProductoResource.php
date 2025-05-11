<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoResource\Pages;
use App\Filament\Resources\ProductoResource\RelationManagers;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;


use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;
    // Atributos de la tabla
    // 'cod_prod',
    // 'descripcion',
    // 'descripcion_detallada',
    // 'peso_unitario',
    // 'activo'

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';
    protected static ?string $navigationGroup = 'Productos';
    protected static ?string $navigationLabel = 'Productos';
    protected static ?string $label = 'Producto';
    protected static ?string $pluralLabel = 'Productos';
    protected static ?string $slug = 'productos';
    protected static ?string $modelLabel = 'Producto';
    protected static ?string $modelPluralLabel = 'Productos';

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
                        TextInput::make('cod_prod')
                            ->label('Código de Producto')
                            ->required()
                            ->columnSpan([
                                'sm' => 4,
                                'xl' => 2,
                                '2xl' => 1,
                            ])
                            ->maxLength(20),
                        TextInput::make('descripcion')
                            ->label('Descripción')
                            ->required()
                            ->columnSpan([
                                'sm' => 4,
                                'xl' => 2,
                                '2xl' => 2,
                            ])
                            ->maxLength(100),
                        TextInput::make('peso_unitario')
                            ->label('Peso Unitario (Kg)')
                            ->numeric()
                            ->required()
                            ->maxLength(10)
                            ->default(0),
                        Textarea::make('descripcion_detallada')
                            ->label('Descripción Detallada')
                            ->columnSpan('full'),
                        Toggle::make('activo')
                            ->label('Activo')
                            ->default(Producto::ACTIVO)
                            ->required()
                            ->inline(false)
                            ->reactive(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cod_prod')
                    ->label('Código de Producto')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('descripcion_detallada')
                    ->label('Descripción Detallada')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('peso_unitario')
                    ->label('Peso Unitario')
                    ->sortable()
                    ->searchable(),
                // Tables\Columns\TextColumn::make('activo')
                //     ->label('Estado')
                //     ->sortable()
                //     ->searchable()
                //     ->boolean()
                //     ->trueIcon('heroicon-o-check')
                //     ->falseIcon('heroicon-o-x'),
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
            'index' => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProducto::route('/crear'),
            'edit' => Pages\EditProducto::route('/{record}/editar'),
        ];
    }
}
