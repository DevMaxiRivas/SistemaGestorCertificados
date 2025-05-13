<?php

namespace App\Filament\Resources\RemitoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DetallesRemitoRelationManager extends RelationManager
{
    protected static string $relationship = 'detalles_remito';
    protected static ?string $recordTitleAttribute = 'producto.cod_prod';
    protected static ?string $label = 'Detalle del Remito';
    protected static ?string $pluralLabel = 'Detalles del Remito';
    protected static ?string $title = 'Detalle del Remito';
    protected static ?string $modelLabel = 'Detalle del Remito';
    protected static ?string $modelPluralLabel = 'Detalles del Remito';
    protected static ?string $navigationGroup = 'Pedidos';
    protected static ?string $navigationLabel = 'Detalles del Remito';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_producto')
                    ->label('Código de Producto')
                    ->searchable()
                    ->relationship('producto', 'cod_prod')
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        return $record->cod_prod . ' - ' . $record->descripcion;
                    })
                    ->columnSpan('full')
                    ->required(),
                Forms\Components\TextInput::make('peso')
                    ->numeric()
                    ->postfix("kg")
                    ->maxLength(255),
                Forms\Components\TextInput::make('cantidad')
                    ->numeric()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('producto.cod_prod')
            ->columns([
                Tables\Columns\TextColumn::make('producto.cod_prod')
                    ->sortable(),
                Tables\Columns\TextColumn::make('producto.descripcion')
                    ->label('Descripción')
                    ->sortable(),
                Tables\Columns\TextColumn::make('peso')
                    ->label('Peso')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->sortable(),
            ])
            ->filters([
                // 
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}