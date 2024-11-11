<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonografResource\Pages;
use App\Filament\Resources\MonografResource\RelationManagers;
use App\Models\Monograf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MonografResource extends Resource
{
    protected static ?string $model = Monograf::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tipeDok')
                    ->required()
                    ->maxLength(255)
                    ->default('Monografi'),
                Forms\Components\TextInput::make('jenisDok')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('judul')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('teuBadan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tahunTerbit')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tempatPenetapan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('penerbit')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tglPenetapan')
                    ->required(),
                Forms\Components\DatePicker::make('tglPengundangan')
                    ->required(),
                Forms\Components\TextInput::make('noPanggil')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cetakanEdisi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('deskripsiFisik')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('subjek')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('isbn')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('bahasa')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lokasi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('bidangHukum')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lampiran')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nomorIndukBuku')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tipeDok')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenisDok')
                    ->searchable(),
                Tables\Columns\TextColumn::make('teuBadan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahunTerbit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tempatPenetapan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('penerbit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tglPenetapan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tglPengundangan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('noPanggil')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cetakanEdisi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deskripsiFisik')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subjek')
                    ->searchable(),
                Tables\Columns\TextColumn::make('isbn')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bahasa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lokasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bidangHukum')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lampiran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomorIndukBuku')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListMonografs::route('/'),
            'create' => Pages\CreateMonograf::route('/create'),
            'edit' => Pages\EditMonograf::route('/{record}/edit'),
        ];
    }
}
