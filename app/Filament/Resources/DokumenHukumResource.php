<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DokumenHukumResource\Pages;
use App\Filament\Resources\DokumenHukumResource\RelationManagers;
use App\Models\DokumenHukum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DokumenHukumResource extends Resource
{
    protected static ?string $model = DokumenHukum::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';

    protected static ?string $navigationGroup = 'Dokumen Hukum';
    protected static ?int $navigationSort = 1;

    protected static ?string $label = 'Dokumen';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $navigationLabel = 'Dokumen'; // Memberikan Label Navigasi

    protected static function getYearsOptions(): array
    {
        // Menghasilkan daftar tahun secara dinamis
        $years = range(date('Y'), 2000); // Ubah rentang tahun sesuai kebutuhan
        return array_combine($years, $years); // Membuat opsi array untuk dropdown
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListDokumenHukums::route('/'),
            'create' => Pages\CreateDokumenHukum::route('/create'),
            'edit' => Pages\EditDokumenHukum::route('/{record}/edit'),
        ];
    }
}
