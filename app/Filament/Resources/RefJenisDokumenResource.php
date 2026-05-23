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
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\Alignment;

class RefJenisDokumenResource extends Resource
{
    protected static ?string $model = RefJenisDokumen::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-link';

    protected static \UnitEnum|string|null $navigationGroup = 'Metadata Referensi';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Data Jenis Dokumen';

    protected static ?string $pluralLabel = 'Jenis Dokumen';

    protected static ?string $label = 'Jenis Dokumen';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('tipe_dokumen_id')
                    ->label('Tipe Dokumen')
                    ->relationship(
                        name: 'tipeDokumen',
                        titleAttribute: 'nama',
                        modifyQueryUsing: fn($query) => $query->where('is_active', true)->orderBy('urutan')
                    )
                    ->required()
                    ->preload()
                    ->helperText('Pilih tipe dokumen induk'),

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
                Tables\Columns\TextColumn::make('tipeDokumen.nama')
                    ->label('TIPE DOKUMEN')
                    ->badge()
                    ->color('primary')
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disabledClick(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('JENIS DOKUMEN')
                    ->searchable()
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disabledClick(),

                Tables\Columns\TextColumn::make('singkatan')
                    ->label('SINGKATAN')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disabledClick(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('AKTIF / TIDAK AKTIF')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->afterStateUpdated(function ($state, $record) {
                        $record->save();
                    })
                    ->disabledClick(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('UPDATE')
                    ->timezone('Asia/Jakarta')
                    ->date('j M Y')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disabledClick(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe_dokumen_id')
                    ->label('Tipe Dokumen')
                    ->relationship('tipeDokumen', 'nama')
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif / Tidak Aktif')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->nullable(),
            ])
            ->groups([
                Tables\Grouping\Group::make('tipeDokumen.nama')
                    ->label('Tipe Dokumen')
                    ->collapsible(),
            ])
            ->actions([
                ActionGroup::make([
                    \Filament\Actions\EditAction::make()
                        ->modalHeading('Edit Jenis Dokumen'),
                    \Filament\Actions\DeleteAction::make(),
                ])
                    ->button()
                    ->label('Aksi')
            ])
            ->bulkActions([
                \Filament\Actions\DeleteBulkAction::make(),
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

