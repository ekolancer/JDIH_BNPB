<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RefSubjekResource\Pages;
use App\Filament\Resources\RefSubjekResource\RelationManagers;
use App\Models\RefSubjek;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\Alignment;

class RefSubjekResource extends Resource
{
    protected static ?string $model = RefSubjek::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-link';

    protected static \UnitEnum|string|null $navigationGroup = 'Metadata Referensi';

    protected static ?string $navigationLabel = 'Subjek';

    protected static ?string $pluralLabel = 'Subjek';

    protected static ?string $label = 'Subjek Hukum';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kategori')
                    ->maxLength(150),
                Forms\Components\Textarea::make('deskripsi')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                ->label('NAMA')
                    ->searchable()
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->disabledClick(), // Menonaktifkan clickable pada list table
                Tables\Columns\TextColumn::make('kategori')
                ->label('KATEGORI')
                    ->searchable()
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disabledClick(), // Menonaktifkan clickable pada list table

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('AKTIF / TIDAK AKTIF')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->onColor('success') // Hijau untuk "Publikasi"
                    ->offColor('danger') // Merah untuk "Draft"
                    ->onIcon('heroicon-o-check-circle') // Ikon untuk Published
                    ->offIcon('heroicon-o-x-circle') // Ikon untuk Draft
                    ->afterStateUpdated(function ($state, $record) {
                        // Simpan perubahan ke database setelah konversi
                        $record->save();
                    })
                    ->disabledClick(), // Menonaktifkan clickable pada list table

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('UPDATE')
                    ->timezone('Asia/Jakarta')
                    ->date('j M Y')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disabledClick(), // Menonaktifkan clickable pada list table

            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    \Filament\Actions\EditAction::make()
                        ->modalHeading('Edit Subjek'),
                    \Filament\Actions\DeleteAction::make(),
                ])
                    ->button()
                    ->label('Aksi')
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
            'index' => Pages\ListRefSubjeks::route('/'),
            'create' => Pages\CreateRefSubjek::route('/create'),
            'edit' => Pages\EditRefSubjek::route('/{record}/edit'),
        ];
    }
}

