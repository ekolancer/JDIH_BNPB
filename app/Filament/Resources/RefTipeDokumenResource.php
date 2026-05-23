<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RefTipeDokumenResource\Pages;
use App\Models\RefTipeDokumen;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Support\Enums\Alignment;
use Filament\Actions\ActionGroup;

class RefTipeDokumenResource extends Resource
{
    protected static ?string $model = RefTipeDokumen::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-link';

    protected static \UnitEnum|string|null $navigationGroup = 'Metadata Referensi';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Data Tipe Dokumen';

    protected static ?string $pluralLabel = 'Tipe Dokumen';

    protected static ?string $label = 'Tipe Dokumen';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('kode')
                    ->label('Kode')
                    ->helperText('Kode unik (contoh: Peraturan, Monografi)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),

                TextInput::make('nama')
                    ->label('Nama Tipe Dokumen')
                    ->helperText('Nama lengkap (contoh: Peraturan Perundang-undangan)')
                    ->required()
                    ->maxLength(150),

                TextInput::make('urutan')
                    ->label('Urutan Tampil')
                    ->integer()
                    ->default(0)
                    ->minValue(0),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->label('KODE')
                    ->badge()
                    ->color('primary')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->searchable()
                    ->disabledClick(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('NAMA TIPE DOKUMEN')
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->searchable()
                    ->disabledClick(),

                Tables\Columns\TextColumn::make('jenis_dokumen_count')
                    ->label('JUMLAH JENIS')
                    ->counts('jenisDokumen')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->badge()
                    ->color('success')
                    ->disabledClick(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('AKTIF')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->afterStateUpdated(fn($state, $record) => $record->save())
                    ->disabledClick(),

                Tables\Columns\TextColumn::make('urutan')
                    ->label('URUTAN')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->sortable()
                    ->disabledClick(),
            ])
            ->defaultSort('urutan')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->nullable(),
            ])
            ->actions([
                ActionGroup::make([
                    \Filament\Actions\EditAction::make()
                        ->modalHeading('Edit Tipe Dokumen'),
                    \Filament\Actions\DeleteAction::make(),
                ])
                    ->button()
                    ->label('Aksi'),
            ])
            ->bulkActions([
                \Filament\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRefTipeDokumens::route('/'),
            'create' => Pages\CreateRefTipeDokumen::route('/create'),
            'edit'   => Pages\EditRefTipeDokumen::route('/{record}/edit'),
        ];
    }
}
