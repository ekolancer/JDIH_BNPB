<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RefJenisDokumenResource\Pages;
use App\Models\RefJenisDokumen;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Tables\Actions\ActionGroup;
use Filament\Support\Enums\Alignment;

class RefJenisDokumenResource extends Resource
{
    protected static ?string $model = RefJenisDokumen::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-link';

    protected static \UnitEnum|string|null $navigationGroup = 'Metadata Referensi';

    protected static ?string $navigationLabel = 'Jenis Dokumen';

    protected static ?string $pluralLabel = 'Jenis Dokumen';

    protected static ?string $label = 'Jenis Dokumen';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Jenis Dokumen')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(150),

                TextInput::make('singkatan')
                    ->label('Singkatan')
                    ->required()
                    ->maxLength(20)
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('singkatan', strtoupper($state));
                    }),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->maxLength(200)
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('JENIS DOKUMEN')
                    ->searchable()
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disableClick(), // Menonaktifkan clickable pada list table,

                Tables\Columns\TextColumn::make('singkatan')
                    ->label('SINGKATAN')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disableClick(), // Menonaktifkan clickable pada list table

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
                    ->disableClick(), // Menonaktifkan clickable pada list table

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('UPDATE')
                    ->timezone('Asia/Jakarta')
                    ->date('j M Y')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disableClick(), // Menonaktifkan clickable pada list table

            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif / Tidak Aktif')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->nullable(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->modalHeading('Edit Jenis Dokumen'),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->button()
                    ->label('Aksi')
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRefJenisDokumens::route('/'),
            'create' => Pages\CreateRefJenisDokumen::route('/create'),
            'edit'   => Pages\EditRefJenisDokumen::route('/{record}/edit'),
        ];
    }
}
