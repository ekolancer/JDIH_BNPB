<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RefSubjekResource\Pages;
use App\Filament\Resources\RefSubjekResource\RelationManagers;
use App\Models\RefSubjek;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Support\Enums\Alignment;

class RefSubjekResource extends Resource
{
    protected static ?string $model = RefSubjek::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Metadata Referensi';

    protected static ?string $navigationLabel = 'Subjek';

    protected static ?string $pluralLabel = 'Subjek';

    protected static ?string $label = 'Subjek Hukum';

    public static function form(Form $form): Form
    {
        return $form
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
                    ->searchable()
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->disabledClick(), // Menonaktifkan clickable pada list table
                Tables\Columns\TextColumn::make('kategori')
                    ->searchable()
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center),
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
            'index' => Pages\ListRefSubjeks::route('/'),
            'create' => Pages\CreateRefSubjek::route('/create'),
            'edit' => Pages\EditRefSubjek::route('/{record}/edit'),
        ];
    }
}
