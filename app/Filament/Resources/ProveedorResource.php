<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProveedorResource\Pages;
use App\Filament\Resources\ProveedorResource\RelationManagers;
use App\Models\Proveedor;

use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;

use Filament\Tables\Columns\TextColumn;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProveedorResource extends Resource
{
    protected static ?string $model = Proveedor::class;
    // Atributos de la tabla
    // 'razon_social',
    // 'cuit', 
    // 'direccion', 
    // 'telefono',
    // 'email', 
    // 'activo'

    protected static ?string $navigationGroup = 'Proveedores';
    protected static ?string $navigationLabel = 'Proveedores';
    protected static ?string $label = 'Proveedor';
    protected static ?string $pluralLabel = 'Proveedores';
    protected static ?string $slug = 'proveedores';


    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    Section::make()
                        ->columns([
                            'sm' => 1,
                            'xl' => 2,
                            '2xl' => 2,
                        ])
                        ->schema([
                            TextInput::make('razon_social')
                                ->label('Razón social')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('cuit')
                                ->numeric()
                                ->label('CUIT')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('direccion')
                                ->label('Dirección')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan([
                                    'sm' => 1,
                                    'xl' => 2,
                                    '2xl' => 2
                                ]),
                            TextInput::make('telefono')
                                ->label('Teléfono')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label('Correo electrónico')
                                ->required()
                                ->maxLength(255),
                        ]),
                ]
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('razon_social')
                    ->label('Razón social')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cuit')
                    ->label('CUIT')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('direccion')
                    ->label('Dirección')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListProveedors::route('/'),
            'create' => Pages\CreateProveedor::route('/create'),
            'edit' => Pages\EditProveedor::route('/{record}/edit'),
        ];
    }
}
