<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PutusanResource\Pages;
use App\Filament\Resources\PutusanResource\RelationManagers;
use App\Models\Putusan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PutusanResource extends Resource
{
    protected static ?string $model = Putusan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tipeDok')
                    ->required()
                    ->maxLength(255)
                    ->default('Putusan Hukum'),
                Forms\Components\TextInput::make('jenisDok')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('judul')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('teuBadan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nomor')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tahunTerbit')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('singkatan')
                    ->required()
                    ->maxLength(255),
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
                Forms\Components\TextInput::make('subjek')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sumber')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required(),
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
                Forms\Components\TextInput::make('penandatangan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('dokumenTerkait')
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
                Tables\Columns\TextColumn::make('nomor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahunTerbit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('singkatan')
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('subjek')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sumber')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('bahasa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lokasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bidangHukum')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lampiran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('penandatangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dokumenTerkait')
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
            'index' => Pages\ListPutusans::route('/'),
            'create' => Pages\CreatePutusan::route('/create'),
            'edit' => Pages\EditPutusan::route('/{record}/edit'),
        ];
    }
}
