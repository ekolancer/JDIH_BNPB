<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfilPageResource\Pages;
use App\Filament\Resources\ProfilPageResource\RelationManagers;
use App\Models\ProfilPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Support\Enums\Alignment;

class ProfilPageResource extends Resource
{
    protected static ?string $model = ProfilPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?string $navigationGroup = 'Features';

    protected static ?string $navigationLabel = 'Profil Website';

    protected static ?string $pluralLabel = 'Profil Website';

    protected static ?string $label = 'Profil';

    public static function getNavigationSort(): ?int
    {
        return 1; // atau angka berapapun
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                TextInput::make('slug')
                    ->label('Slug (Identifier)'),

                RichEditor::make('content')
                    ->label('Isi Konten')
                    ->columnSpan('full'),

                FileUpload::make('image')
                    ->label('Gambar Struktur Organisasi')
                    ->image()
                    ->directory('struktur-organisasi')
                    ->visibility('public')
                    ->columnSpan('full')
                    ->hidden(fn($record) => $record?->slug !== 'struktur-organisasi'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disableClick(), // Menonaktifkan clickable pada list table
                TextColumn::make('slug')
                    ->label('Slug')->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disableClick(), // Menonaktifkan clickable pada list table
                TextColumn::make('updated_at')
                    ->label('Update Terakhir')
                    ->date()
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disableClick(), // Menonaktifkan clickable pada list table
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
            'index' => Pages\ListProfilPages::route('/'),
            'create' => Pages\CreateProfilPage::route('/create'),
            'edit' => Pages\EditProfilPage::route('/{record}/edit'),
        ];
    }
}
