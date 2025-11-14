<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RefJenisDokumenResource\Pages;
use App\Models\RefJenisDokumen;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Support\Enums\Alignment;

class RefJenisDokumenResource extends Resource
{
    protected static ?string $model = RefJenisDokumen::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Metadata Referensi';

    protected static ?string $navigationLabel = 'Jenis Dokumen';

    protected static ?string $pluralLabel = 'Jenis Dokumen';

    protected static ?string $label = 'Jenis Dokumen';

    public static function form(Form $form): Form
    {
        return $form
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
                    ->label('Jenis Dokumen')
                    ->searchable()
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disableClick(), // Menonaktifkan clickable pada list table,
                    
                Tables\Columns\TextColumn::make('singkatan')
                    ->label('Singkatan')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('d M Y - H:i')
                    ->sortable()
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif / Tidak Aktif')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->nullable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
