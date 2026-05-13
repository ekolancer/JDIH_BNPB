<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfilPageResource\Pages;
use App\Models\ProfilPage;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Support\Enums\Alignment;
use Filament\Actions\ActionGroup;

class ProfilPageResource extends Resource
{
    protected static ?string $model = ProfilPage::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-computer-desktop';

    protected static \UnitEnum|string|null $navigationGroup = 'Features';

    protected static ?string $navigationLabel = 'Profile Website';

    protected static ?string $pluralLabel = 'Profile Website';

    protected static ?string $label = 'Profile';

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
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
                    ->label('JUDUL')
                    ->searchable()
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disabledClick(),
                TextColumn::make('slug')
                    ->label('SLUG')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disabledClick(),
                TextColumn::make('updated_at')
                    ->label('UPDATE')
                    ->timezone('Asia/Jakarta')
                    ->date('j M Y')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disabledClick(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    \Filament\Actions\EditAction::make()
                        ->modalHeading('Edit Profil'),
                    \Filament\Actions\DeleteAction::make(),
                ])
                    ->button()
                    ->label('Aksi'),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
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
