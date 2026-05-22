<?php

namespace App\Filament\Resources\DokumenHukumResource\RelationManagers;

use App\Models\DokumenHukum;
use App\Models\DokumenRelasi;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DokumenRelasiRelationManager extends RelationManager
{
    protected static string $relationship = 'relasiKeluar';

    protected static ?string $title = 'Relasi Dokumen';

    protected static ?string $recordTitleAttribute = 'jenis_relasi';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('related_dokumen_id')
                    ->label('Dokumen Terkait')
                    ->searchable()
                    ->preload()
                    ->options(function () {
                        $currentId = $this->getOwnerRecord()->id;

                        return DokumenHukum::where('id', '!=', $currentId)
                            ->orderByDesc('tahun')
                            ->limit(200)
                            ->get()
                            ->mapWithKeys(fn ($doc) => [
                                $doc->id => "[{$doc->tahun}] {$doc->jenisDokumen?->singkatan} No. {$doc->nomor} — " . \Illuminate\Support\Str::limit($doc->judul, 60),
                            ])
                            ->toArray();
                    })
                    ->required()
                    ->validationMessages([
                        'required' => 'Pilih dokumen terkait.',
                    ]),

                Forms\Components\Select::make('jenis_relasi')
                    ->label('Jenis Relasi')
                    ->options(DokumenRelasi::getRelasiOptions())
                    ->required()
                    ->native(false),

                Forms\Components\Textarea::make('catatan')
                    ->label('Catatan')
                    ->placeholder('Contoh: Pasal 3 ayat 2')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('relatedDokumen.judul')
                    ->label('Dokumen Terkait')
                    ->limit(60)
                    ->searchable(),

                Tables\Columns\TextColumn::make('relatedDokumen.tahun')
                    ->label('Tahun')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jenis_relasi')
                    ->label('Jenis Relasi')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => DokumenRelasi::getAllRelasiLabels()[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'mencabut', 'dicabut_oleh'             => 'danger',
                        'mengubah', 'diubah_oleh'              => 'warning',
                        'melaksanakan', 'dilaksanakan_oleh'    => 'info',
                        'melengkapi', 'dilengkapi_oleh'        => 'success',
                        'dasar_hukum', 'menjadi_dasar'         => 'primary',
                        'terkait'                              => 'gray',
                        default                                => 'gray',
                    }),

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(40)
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ditambahkan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->label('Tambah Relasi')
                    ->after(function (DokumenRelasi $record) {
                        $this->createInverseRelasi($record);
                    }),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make()
                    ->after(function (DokumenRelasi $record) {
                        $this->deleteInverseRelasi($record);
                    }),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Buat record inverse otomatis.
     */
    private function createInverseRelasi(DokumenRelasi $record): void
    {
        $inverse = DokumenRelasi::getInverseRelasi($record->jenis_relasi);

        DokumenRelasi::firstOrCreate([
            'dokumen_id'         => $record->related_dokumen_id,
            'related_dokumen_id' => $record->dokumen_id,
            'jenis_relasi'       => $inverse,
        ], [
            'catatan' => $record->catatan,
        ]);
    }

    /**
     * Hapus record inverse otomatis.
     */
    private function deleteInverseRelasi(DokumenRelasi $record): void
    {
        $inverse = DokumenRelasi::getInverseRelasi($record->jenis_relasi);

        DokumenRelasi::where('dokumen_id', $record->related_dokumen_id)
            ->where('related_dokumen_id', $record->dokumen_id)
            ->where('jenis_relasi', $inverse)
            ->delete();
    }
}
