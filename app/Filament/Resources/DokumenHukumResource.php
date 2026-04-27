<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DokumenHukum;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Actions\Action;
use App\Models\RefBidangHukum;
use App\Models\RefSubjek;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Enums\VerticalAlignment;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DokumenHukumResource\Pages;
use App\Filament\Resources\DokumenHukumResource\RelationManagers;

use function Laravel\Prompts\select;

class DokumenHukumResource extends Resource
{
    protected static ?string $model = DokumenHukum::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Main Menu';

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Section Document')
                    ->schema([
                        Tabs::make('Input Dokumen')
                            ->tabs([
                                Tab::make('Informasi Dokumen')
                                    ->icon('heroicon-m-information-circle')
                                    ->iconPosition(IconPosition::After)
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
                                            ->helperText('Pilih tipe dokumen yang sesuai sebelum mengisi metadata')
                                            ->reactive()
                                            ->required(),
                                        Select::make('ref_jenis_dokumen_id')
                                            ->label('Jenis Dokumen')
                                            ->relationship(
                                                name: 'jenisDokumen',
                                                titleAttribute: 'nama',
                                                modifyQueryUsing: fn($query) => $query->where('is_active', true)
                                            )
                                            ->preload()
                                            ->live()
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
                                            ->dehydrated(false)
                                            ->disabled()
                                            ->afterStateHydrated(function (callable $set, $get) {
                                                if ($id = $get('ref_jenis_dokumen_id')) {
                                                    $set('singkatan', \App\Models\RefJenisDokumen::find($id)?->singkatan);
                                                }
                                            })
                                            ->visible(fn(Get $get) => in_array($get('tipeDok'), ['Peraturan', 'Putusan'])),
                                        Textarea::make('judul')
                                            ->label('Judul')
                                            ->rows(5)
                                            ->cols(10)
                                            ->validationMessages(['required' => 'Judul tidak boleh kosong !'])
                                            ->required(),
                                        TextInput::make('teuBadan')
                                            ->validationMessages(['required' => 'T.E.U Badan tidak boleh kosong !'])
                                            ->label('T.E.U Badan')
                                            ->required(),
                                        Select::make('status')
                                            ->options([
                                                'BERLAKU'   => 'Berlaku',
                                                'TIDAK BERLAKU' => 'Tidak Berlaku',
                                            ])
                                            ->validationMessages(['required' => 'Status tidak boleh kosong !'])
                                            ->visible(fn(Get $get): bool => $get('tipeDok') === 'Peraturan') // Hanya terlihat jika tipeDok adalah Peraturan
                                            ->required(fn(Get $get): bool => $get('tipeDok') === 'Peraturan'), // Wajib diisi jika tipeDok adalah Peraturan  
                                    ]),
                                Tab::make('Metadata Dokumen')
                                    ->icon('heroicon-m-list-bullet')
                                    ->iconPosition(IconPosition::After)
                                    ->schema([
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
                                        Fieldset::make('Tanggal dan Tempat Terbit')
                                            ->schema([
                                                DatePicker::make('tglPenetapan')
                                                    ->label('Tanggal Penetapan Peraturan')
                                                    ->validationMessages(['required' => 'Tanggal Penetapan tidak boleh kosong !'])
                                                    ->format('Y/m/d')
                                                    ->visible(fn(Get $get): bool => $get('tipeDok') === 'Peraturan') // Hanya terlihat jika tipeDok adalah Peraturan
                                                    ->required(fn(Get $get): bool => $get('tipeDok') === 'Peraturan'), // Wajib diisi jika tipeDok adalah Peraturan  
                                                DatePicker::make('tglPengundangan')
                                                    ->label('Tanggal Pengundangan Peraturan')
                                                    ->validationMessages(['required' => 'Tanggal Pengundangan dokumen tidak boleh kosong !'])
                                                    ->format('Y/m/d')
                                                    ->visible(fn(Get $get): bool => $get('tipeDok') === 'Peraturan') // Hanya terlihat jika tipeDok adalah Peraturan
                                                    ->required(fn(Get $get): bool => $get('tipeDok') === 'Peraturan'), // Wajib diisi jika tipeDok adalah Peraturan  
                                                DatePicker::make('tglDiBacakan')
                                                    ->label('Tanggal Dibacakan')
                                                    ->validationMessages(['required' => 'Tanggal Dibacakan tidak boleh kosong !'])
                                                    ->format('Y/m/d')
                                                    ->visible(fn(Get $get): bool => $get('tipeDok') === 'Putusan') // Hanya terlihat jika tipeDok adalah Putusan
                                                    ->required(fn(Get $get): bool => $get('tipeDok') === 'Putusan'), // Hanya terlihat jika tipeDok adalah Putusan
                                                TextInput::make('tempat')
                                                    ->label('Tempat Terbit')
                                                    ->validationMessages(['required' => 'Tempat Penetapan tidak boleh kosong !'])
                                                    ->required(),
                                            ])
                                            ->columns(2), // Membagi dalam 2 kolom responsif
                                        TextInput::make('penerbit')
                                            ->label('Penerbit')
                                            ->validationMessages(['required' => 'Penerbit tidak boleh kosong !'])
                                            ->visible(fn(Get $get): bool => in_array($get('tipeDok'), ['Monografi', 'Artikel', 'Putusan']))
                                            ->required(fn(Get $get): bool => in_array($get('tipeDok'), ['Monografi', 'Artikel', 'Putusan'])),
                                        TextInput::make('deskripsiFisik')
                                            ->label('Deskripsi Fisik')
                                            ->validationMessages(['required' => 'Deskripsi Fisik tidak boleh kosong!'])
                                            ->visible(fn(Get $get): bool => $get('tipeDok') === 'Monografi') // Hanya terlihat jika tipeDok adalah Monografi
                                            ->required(fn(Get $get): bool => $get('tipeDok') === 'Monografi'), // Wajib diisi jika tipeDok adalah Monografi                                        
                                        TextInput::make('cetakanEdisi')
                                            ->label('Cetakan Edisi')
                                            ->validationMessages(['required' => 'Penerbit tidak boleh kosong !'])
                                            ->visible(fn(Get $get): bool => $get('tipeDok') === 'Monografi') // Hanya terlihat jika tipeDok adalah Monografi
                                            ->required(fn(Get $get): bool => $get('tipeDok') === 'Monografi'), // Wajib diisi jika tipeDok adalah Monografi
                                        TextInput::make('nomorPanggil')
                                            ->label('Nomor Panggil')
                                            ->validationMessages(['required' => 'Penerbit tidak boleh kosong !'])
                                            ->integer()
                                            ->visible(fn(Get $get): bool => $get('tipeDok') === 'Monografi') // Hanya terlihat jika tipeDok adalah Monografi
                                            ->required(fn(Get $get): bool => $get('tipeDok') === 'Monografi'), // Wajib diisi jika tipeDok adalah Monografi
                                        TextInput::make('isbn')
                                            ->label('ISSN / ISBN')
                                            ->validationMessages(['required' => 'ISSN / ISBN tidak boleh kosong !'])
                                            ->visible(fn(Get $get): bool => $get('tipeDok') === 'Monografi') // Hanya terlihat jika tipeDok adalah Monografi
                                            ->required(fn(Get $get): bool => $get('tipeDok') === 'Monografi'), // Wajib diisi jika tipeDok adalah Monografi
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
                                        TextInput::make('sumber')
                                            ->label('Sumber')
                                            ->validationMessages(['required' => 'Sumber tidak boleh kosong !'])
                                            ->hidden(fn(Get $get): bool => $get('tipeDok') === 'Monografi') // Hanya tersembunyi jika tipeDok adalah Monografi
                                            ->required(fn(Get $get): bool => in_array($get('tipeDok'), ['Peraturan', 'Artikel', 'Putusan'])),
                                        TextInput::make('bahasa')
                                            ->validationMessages(['required' => 'Bahasa tidak boleh kosong !'])
                                            ->label('Bahasa')
                                            ->required(),
                                        TextInput::make('lokasi')
                                            ->validationMessages(['required' => 'Lokasi tidak boleh kosong !'])
                                            ->label('Lokasi')
                                            ->required(),
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
                                        TextInput::make('nomorIndukBuku')
                                            ->validationMessages(['required' => 'Nomor Induk Buku tidak boleh kosong !'])
                                            ->label('Nomor Induk Buku')
                                            ->integer()
                                            ->visible(fn(Get $get): bool => $get('tipeDok') === 'Monografi') // Hanya terlihat jika tipeDok adalah Monografi
                                            ->required(fn(Get $get): bool => $get('tipeDok') === 'Monografi'), // Hanya terlihat jika tipeDok adalah Monografi
                                        TextInput::make('penandatangan')
                                            ->label('Penandatangan')
                                            ->visible(fn(Get $get): bool => in_array($get('tipeDok'), ['Peraturan', 'Putusan'])),
                                    ]),
                                Tab::make('Lampiran Dokumen')
                                    ->icon('heroicon-m-inbox-arrow-down')
                                    ->iconPosition(IconPosition::After)
                                    ->schema([
                                        TextInput::make('slug')
                                            ->disabled()
                                            ->helperText('Slug dihasilkan otomatis'),
                                        FileUpload::make('lampiran')
                                            ->disk('public')
                                            ->directory('file')
                                            ->visibility('public')
                                            ->preserveFilenames()
                                            ->acceptedFileTypes([
                                                'application/pdf', // PDF
                                                'image/jpeg',      // JPEG
                                                'image/png',       // PNG
                                                'image/jpg',       // JPG (opsional, jika dibutuhkan terpisah dari JPEG)
                                            ])
                                            ->validationMessages(['required' => 'Lampiran tidak boleh kosong !'])
                                            ->maxSize(25600)
                                            ->required(),
                                        FileUpload::make('abstrak')
                                            ->disk('public')
                                            ->directory('abstrak')
                                            ->visibility('public')
                                            ->preserveFilenames()
                                            ->acceptedFileTypes(['application/pdf'])
                                            ->visible(fn(Get $get): bool => $get('tipeDok') === 'Peraturan') // Hanya terlihat jika tipeDok adalah Peraturan
                                            ->maxSize(25600),
                                    ]),
                            ])
                            ->columns(2), // Memastikan full-width untuk Tabs
                    ])
                    ->columns(1), // Memastikan full-width untuk Section
            ]);
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
                    ->disableClick(), // Menonaktifkan clickable pada list table
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
                    Tables\Actions\Action::make('detail')
                        ->label('Detail')
                        ->icon('heroicon-o-eye')
                        ->color('warning')
                        ->slideover()
                        ->modalWidth('5xl')
                        ->modalHeading(fn(DokumenHukum $record) => "{$record->judul}")
                        ->modalContent(fn(DokumenHukum $record) => view(
                            'filament.pages.action.dokumen-view',
                            ['record' => $record]
                        ))
                        ->modalSubmitAction(false),
                    Tables\Actions\EditAction::make()
                        ->modalHeading('Edit Data'),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->button()
                    ->label('Aksi'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
