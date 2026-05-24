<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfilPageResource\Pages;
use App\Models\ProfilPage;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
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
                    ->label('Slug (Identifier)')
                    ->required()
                    ->unique(ProfilPage::class, 'slug', ignoreRecord: true)
                    ->disabled(fn (?ProfilPage $record) => $record !== null)
                    ->dehydrated()
                    ->helperText(fn (?ProfilPage $record) => $record
                        ? 'Slug tidak dapat diubah setelah dibuat.'
                        : 'Masukkan slug unik untuk halaman ini.'),

                RichEditor::make('content')
                    ->label('Isi Konten')
                    ->columnSpan('full'),

                Toggle::make('has_image')
                    ->label('Aktifkan Gambar')
                    ->helperText('Aktifkan untuk menampilkan field upload gambar.')
                    ->live()
                    ->columnSpan('full'),

                FileUpload::make('image')
                    ->label('Gambar')
                    ->image()
                    ->directory(fn (?ProfilPage $record) => 'profil/' . ($record?->slug ?? 'temp'))
                    ->visibility('public')
                    ->columnSpan('full')
                    ->visible(fn ($get) => (bool) $get('has_image')),
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
                    ->timezone(config('app.timezone', 'Asia/Jakarta'))
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
