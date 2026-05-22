<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use App\Models\DokumenHukum;
use Filament\Resources\Resource;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Schemas\Components\Fieldset;
use Filament\Support\Enums\FontWeight;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Actions\CreateAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Enums\VerticalAlignment;
use App\Filament\Resources\DokumenHukumResource\Pages;
use App\Filament\Resources\DokumenHukumResource\RelationManagers;

class DokumenHukumResource extends Resource
{
    protected static ?string $model = DokumenHukum::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-book-open';

    protected static \UnitEnum|string|null $navigationGroup = 'Main Menu';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'list-dokumen';

    protected static ?string $breadcrumb = 'Dokumen';

    protected static ?string $label = 'Dokumen';

    protected static ?string $navigationLabel = 'Dokumen'; // Memberikan Label Navigasi

    protected static function getYearsOptions(): array
    {
        // Menghasilkan daftar tahun secara dinamis
        $years = range(date('Y'), 2000); // Ubah rentang tahun sesuai kebutuhan
        return array_combine($years, $years); // Membuat opsi array untuk dropdown
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Wizard::make(static::getFormSteps())
                    ->submitAction(new \Illuminate\Support\HtmlString(\Illuminate\Support\Facades\Blade::render(<<<BLADE
                        <x-filament::button
                            type="submit"
                            size="sm"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove>Submit</span>
                            <x-filament::loading-indicator wire:loading class="h-5 w-5" />
                        </x-filament::button>
                    BLADE)))
                    ->columnSpanFull(),
            ]);
    }

    public static function getFormSteps(): array
    {
        return [
            Step::make('Tipe Dokumen')
                ->icon('heroicon-m-document-text')
                ->description('Pilih tipe dan jenis dokumen')
                ->schema([
                    Select::make('tipeDok')
                        ->label('Tipe Dokumen')
                        ->options([
                            'Peraturan' => 'Peraturan Perundang-undangan',
                            'Monografi' => 'Monografi',
                            'Artikel' => 'Artikel',
                            'Putusan' => 'Putusan Pengadilan',
                        ])
                        ->validationMessages([
                            'required' => 'Tipe Dokumen harus diisi',
                        ])
                        ->helperText('Pilih tipe dokumen yang sesuai. Field pada langkah berikutnya akan menyesuaikan tipe yang dipilih.')
                        ->reactive()
                        ->required(),
                    Select::make('ref_jenis_dokumen_id')
                        ->label('Jenis Dokumen')
                        ->required()
                        ->relationship(
                            name: 'jenisDokumen',
                            titleAttribute: 'nama',
                            modifyQueryUsing: fn($query) => $query->where('is_active', true)
                        )
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function (callable $set, $state) {
                            if ($state) {
                                $singkatan = \App\Models\RefJenisDokumen::find($state)?->singkatan;
                                $set('singkatan', $singkatan);
                            } else {
                                $set('singkatan', null);
                            }
                        })
                        ->createOptionForm([
                            Forms\Components\TextInput::make('nama')
                                ->label('Nama Jenis Dokumen')
                                ->required()
                                ->unique(ignoreRecord: true),
                            Forms\Components\TextInput::make('singkatan')
                                ->label('Singkatan')
                                ->required(),
                            Forms\Components\Textarea::make('deskripsi')
                                ->label('Deskripsi')
                                ->nullable(),
                            Forms\Components\Toggle::make('is_active')
                                ->label('Aktif')
                                ->default(true),
                        ]),
                    TextInput::make('singkatan')
                        ->label('Singkatan Jenis')
                        ->hidden()
                        ->dehydrated(false)
                        ->disabled()
                        ->afterStateHydrated(function (callable $set, $get) {
                            if ($id = $get('ref_jenis_dokumen_id')) {
                                $set('singkatan', \App\Models\RefJenisDokumen::find($id)?->singkatan);
                            }
                        })
                        ->visible(fn(Get $get) => in_array($get('tipeDok'), ['Peraturan', 'Putusan'])),
                ])
                ->columns(2),

            Step::make('Informasi Dokumen')
                ->icon('heroicon-m-information-circle')
                ->description('Isi informasi dasar dokumen')
                ->afterValidation(function (callable $get, callable $set) {
                    $tipeDok = $get('tipeDok');
                    $jenisId = $get('ref_jenis_dokumen_id');
                    $nomor = $get('nomor');
                    $tahun = $get('tahun');
                    $judul = $get('judul');

                    $singkatan = $jenisId ? (\App\Models\RefJenisDokumen::find($jenisId)?->singkatan ?? '') : '';

                    if (in_array($tipeDok, ['Putusan', 'Peraturan']) && $nomor && $tahun) {
                        $slug = \Illuminate\Support\Str::slug("{$singkatan}-nomor-{$nomor}-tahun-{$tahun}");
                    } else {
                        $slug = \Illuminate\Support\Str::slug($judul ?? 'dokumen');
                    }

                    $set('slug', $slug);
                })
                ->schema([
                    Textarea::make('judul')
                        ->label('Judul')
                        ->rows(4)
                        ->validationMessages(['required' => 'Judul tidak boleh kosong !'])
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('nomor')
                        ->validationMessages(['required' => 'Nomor tidak boleh kosong !'])
                        ->label('Nomor')
                        ->integer()
                        ->minValue(1)
                        ->maxValue(1000)
                        ->visible(fn(Get $get): bool => in_array($get('tipeDok'), ['Peraturan', 'Putusan']))
                        ->required(fn(Get $get): bool => in_array($get('tipeDok'), ['Peraturan', 'Putusan'])),
                    TextInput::make('tahun')
                        ->validationMessages(['required' => 'Tahun tidak boleh kosong !'])
                        ->label('Tahun')
                        ->integer()
                        ->minValue(1990)
                        ->maxValue(2030)
                        ->required(),
                    TextInput::make('teuBadan')
                        ->validationMessages(['required' => 'T.E.U Badan tidak boleh kosong !'])
                        ->label('T.E.U Badan')
                        ->helperText('Tajuk Entri Utama Badan yang menerbitkan')
                        ->required(),
                    Select::make('status')
                        ->options([
                            'BERLAKU'   => 'Berlaku',
                            'TIDAK BERLAKU' => 'Tidak Berlaku',
                        ])
                        ->validationMessages(['required' => 'Status tidak boleh kosong !'])
                        ->visible(fn(Get $get): bool => $get('tipeDok') === 'Peraturan')
                        ->required(fn(Get $get): bool => $get('tipeDok') === 'Peraturan'),
                ])
                ->columns(2),

            Step::make('Metadata')
                ->icon('heroicon-m-list-bullet')
                ->description('Lengkapi metadata dokumen')
                ->schema([
                    Fieldset::make('Detail Monografi')
                        ->schema([
                            TextInput::make('nomorIndukBuku')
                                ->validationMessages(['required' => 'Nomor Induk Buku tidak boleh kosong !'])
                                ->label('Nomor Induk Buku')
                                ->integer()
                                ->required(),
                            TextInput::make('nomorPanggil')
                                ->label('Nomor Panggil')
                                ->validationMessages(['required' => 'Nomor Panggil tidak boleh kosong !'])
                                ->integer(),
                            TextInput::make('isbn')
                                ->label('ISSN / ISBN')
                                ->validationMessages(['required' => 'ISSN / ISBN tidak boleh kosong !'])
                                ->required(),
                            TextInput::make('deskripsiFisik')
                                ->label('Deskripsi Fisik')
                                ->validationMessages(['required' => 'Deskripsi Fisik tidak boleh kosong!'])
                                ->required(),
                            TextInput::make('cetakanEdisi')
                                ->label('Cetakan / Edisi')
                                ->validationMessages(['required' => 'Cetakan Edisi tidak boleh kosong !'])
                                ->required(),
                        ])
                        ->visible(fn(Get $get): bool => $get('tipeDok') === 'Monografi')
                        ->columnSpanFull(),
                    Fieldset::make('Tanggal dan Tempat Terbit')
                        ->schema([
                            DatePicker::make('tglPenetapan')
                                ->label('Tanggal Penetapan')
                                ->validationMessages(['required' => 'Tanggal Penetapan tidak boleh kosong !'])
                                ->format('Y/m/d')
                                ->visible(fn(Get $get): bool => $get('tipeDok') === 'Peraturan')
                                ->required(fn(Get $get): bool => $get('tipeDok') === 'Peraturan'),
                            DatePicker::make('tglPengundangan')
                                ->label('Tanggal Pengundangan')
                                ->validationMessages(['required' => 'Tanggal Pengundangan tidak boleh kosong !'])
                                ->format('Y/m/d')
                                ->visible(fn(Get $get): bool => $get('tipeDok') === 'Peraturan')
                                ->required(fn(Get $get): bool => $get('tipeDok') === 'Peraturan'),
                            DatePicker::make('tglDiBacakan')
                                ->label('Tanggal Dibacakan')
                                ->validationMessages(['required' => 'Tanggal Dibacakan tidak boleh kosong !'])
                                ->format('Y/m/d')
                                ->visible(fn(Get $get): bool => $get('tipeDok') === 'Putusan')
                                ->required(fn(Get $get): bool => $get('tipeDok') === 'Putusan'),
                            TextInput::make('penerbit')
                                ->label('Penerbit')
                                ->validationMessages(['required' => 'Penerbit tidak boleh kosong !'])
                                ->visible(fn(Get $get): bool => in_array($get('tipeDok'), ['Monografi', 'Artikel', 'Putusan']))
                                ->required(fn(Get $get): bool => in_array($get('tipeDok'), ['Monografi', 'Artikel', 'Putusan'])),
                            TextInput::make('tempat')
                                ->label('Tempat Terbit')
                                ->validationMessages(['required' => 'Tempat Penetapan tidak boleh kosong !'])
                                ->required(),
                        ])
                        ->columnSpanFull(),
                    Select::make('subjekRef')
                        ->label('Subjek')
                        ->multiple()
                        ->relationship(
                            name: 'subjekRef',
                            titleAttribute: 'nama',
                            modifyQueryUsing: fn($query) => $query->where('is_active', 1)
                        )
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('nama')
                                ->label('Nama Subjek')
                                ->required()
                                ->unique(ignoreRecord: true),
                            Forms\Components\TextInput::make('kategori')
                                ->label('Kategori')
                                ->nullable(),
                            Forms\Components\Textarea::make('deskripsi')
                                ->label('Deskripsi')
                                ->nullable(),
                            Forms\Components\Toggle::make('is_active')
                                ->label('Aktif')
                                ->default(true),
                        ]),
                    Select::make('ref_bidang_hukum_id')
                        ->label('Bidang Hukum')
                        ->relationship(
                            name: 'bidangHukumRef',
                            titleAttribute: 'nama',
                            modifyQueryUsing: fn($query) => $query->where('is_active', true)
                        )
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('nama')
                                ->label('Nama Bidang Hukum')
                                ->required(),
                            Forms\Components\Textarea::make('deskripsi')
                                ->nullable(),
                            Forms\Components\Toggle::make('is_active')
                                ->label('Aktif')
                                ->default(true),
                        ]),
                    TextInput::make('sumber')
                        ->label('Sumber')
                        ->validationMessages(['required' => 'Sumber tidak boleh kosong !'])
                        ->hidden(fn(Get $get): bool => $get('tipeDok') === 'Monografi')
                        ->required(fn(Get $get): bool => in_array($get('tipeDok'), ['Peraturan', 'Artikel', 'Putusan'])),
                    TextInput::make('bahasa')
                        ->validationMessages(['required' => 'Bahasa tidak boleh kosong !'])
                        ->label('Bahasa')
                        ->required(),
                    TextInput::make('lokasi')
                        ->validationMessages(['required' => 'Lokasi tidak boleh kosong !'])
                        ->label('Lokasi')
                        ->required(),
                    TextInput::make('penandatangan')
                        ->label('Penandatangan')
                        ->visible(fn(Get $get): bool => in_array($get('tipeDok'), ['Peraturan', 'Putusan'])),
                ])
                ->columns(2),

            Step::make('Lampiran')
                ->icon('heroicon-m-inbox-arrow-down')
                ->description('Upload file dokumen')
                ->schema([
                    TextInput::make('slug')
                        ->disabled()
                        ->helperText('Slug dihasilkan otomatis')
                        ->columnSpanFull(),
                    FileUpload::make('lampiran')
                        ->disk('public')
                        ->directory('file')
                        ->visibility('public')
                        ->preserveFilenames()
                        ->acceptedFileTypes([
                            'application/pdf',
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ])
                        ->validationMessages(['required' => 'Lampiran tidak boleh kosong !'])
                        ->maxSize(25600)
                        ->required()
                        ->columnSpanFull(),
                    FileUpload::make('abstrak')
                        ->disk('public')
                        ->directory('abstrak')
                        ->visibility('public')
                        ->preserveFilenames()
                        ->acceptedFileTypes(['application/pdf'])
                        ->visible(fn(Get $get): bool => $get('tipeDok') === 'Peraturan')
                        ->maxSize(25600)
                        ->columnSpanFull(),
                ]),
        ];
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenisDokumen.nama')
                    ->label('JENIS DOKUMEN')
                    ->description(fn(DokumenHukum $record): string => $record->judul)
                    ->weight(FontWeight::Bold)
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->disabledClick(),
                TextColumn::make('nomor')
                    ->label('NOMOR')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->disabledClick() // Menonaktifkan clickable pada list table
                    ->searchable(),
                TextColumn::make('tahun')
                    ->label('TAHUN')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disabledClick(), // Menonaktifkan clickable pada list table
                TextColumn::make('isbn')
                    ->label('ISBN')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->disabledClick() // Menonaktifkan clickable pada list table
                    ->hidden(fn($record): bool =>
                    $record?->tipeDok !== 'Monografi'),
                TextColumn::make('penerbit')
                    ->label('PENERBIT')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->disabledClick() // Menonaktifkan clickable pada list table
                    ->hidden(fn($record): bool =>
                    $record?->tipeDok !== 'Monografi'),
                TextColumn::make('status')
                    ->label('STATUS')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'BERLAKU' => 'info',
                        'TIDAK BERLAKU' => 'danger',
                    })
                    ->disabledClick(), // Menonaktifkan clickable pada list table
                TextColumn::make('updated_at')
                    ->label('UPDATE')
                    ->timezone('Asia/Jakarta')
                    ->date('j M Y')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disabledClick(), // Menonaktifkan clickable pada list table
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('ref_jenis_dokumen_id')
                    ->label('Jenis Dokumen')
                    ->multiple()
                    ->relationship(
                        'jenisDokumen',
                        'singkatan',
                        fn($query) => $query->where('is_active', true)
                    ),

                SelectFilter::make('subjekRef')
                    ->label('Subjek')
                    ->multiple()
                    ->relationship(
                        'subjekRef',
                        'nama',
                        fn($query) => $query->where('is_active', true)
                    ),

                SelectFilter::make('ref_bidang_hukum_id')
                    ->label('Bidang Hukum')
                    ->relationship(
                        'bidangHukumRef',
                        'nama',
                        fn($query) => $query->where('is_active', true)
                    ),

                SelectFilter::make('tahun')
                    ->label('Tahun')
                    ->options(static::getYearsOptions())
                    ->multiple(),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'BERLAKU' => 'Berlaku',
                        'TIDAK BERLAKU' => 'Tidak Berlaku',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('detail')
                        ->label('Detail')
                        ->icon('heroicon-o-eye')
                        ->color('warning')
                        ->url(fn(DokumenHukum $record) => DokumenHukumResource::getUrl('view', ['record' => $record])),
                    \Filament\Actions\EditAction::make()
                        ->modalHeading('Edit Data'),
                    \Filament\Actions\DeleteAction::make(),
                ])
                    ->button()
                    ->label('Aksi'),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Document'),
            ])
            ->groups([
                Tables\Grouping\Group::make('jenisDokumen.nama')
                    ->label('Jenis Dokumen')
                    ->collapsible(),
            ])
            ->searchPlaceholder('Nomor');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\DokumenRelasiRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDokumenHukums::route('/'),
            'create' => Pages\CreateDokumenHukum::route('/create'),
            'view' => Pages\ViewDokumenHukum::route('/{record}'),
            'edit' => Pages\EditDokumenHukum::route('/{record}/edit'),
        ];
    }
}
