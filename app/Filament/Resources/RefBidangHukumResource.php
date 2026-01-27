<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RefBidangHukumResource\Pages;
use App\Filament\Resources\RefBidangHukumResource\RelationManagers;
use App\Models\RefBidangHukum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Tables\Actions\ActionGroup;
use Filament\Support\Enums\Alignment;

class RefBidangHukumResource extends Resource
{
    protected static ?string $model = RefBidangHukum::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationGroup = 'Metadata Referensi';

    protected static ?string $navigationLabel = 'Bidang Hukum';

    protected static ?string $pluralLabel = 'Bidang Hukum';

    protected static ?string $label = 'Bidang Hukum';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
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
                    ->disableClick(), // Menonaktifkan clickable pada list table
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif')
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
                        ->modalHeading('Edit Bidang Hukum'),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->button()
                    ->label('Aksi')
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
            'index' => Pages\ListRefBidangHukums::route('/'),
            'create' => Pages\CreateRefBidangHukum::route('/create'),
            'edit' => Pages\EditRefBidangHukum::route('/{record}/edit'),
        ];
    }
}
