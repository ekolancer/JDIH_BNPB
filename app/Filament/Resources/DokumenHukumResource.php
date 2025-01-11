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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;

use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\IconPosition;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DokumenHukumResource\Pages;
use App\Filament\Resources\DokumenHukumResource\RelationManagers;

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
                Section::make()
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
                                        TextInput::make('jenisDok')
                                            ->label('Jenis Dokumen')
                                            ->validationMessages(['required' => 'Jenis dokumen tidak boleh kosong !'])
                                            ->required(),
                                        TextInput::make('singkatan')
                                            ->label('Singkatan Jenis')
                                            ->validationMessages(['required' => 'Singkatan tidak boleh kosong !'])
                                            ->visible(fn(Get $get): bool => in_array($get('tipeDok'), ['Peraturan', 'Putusan']))
                                            ->required(fn(Get $get): bool => in_array($get('tipeDok'), ['Peraturan', 'Putusan'])),
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
                                            ->visible(fn(Get $get): bool => in_array($get('tipeDok'), ['Peraturan', 'Putusan']))
                                            ->required(fn(Get $get): bool => in_array($get('tipeDok'), ['Peraturan', 'Putusan'])),
                                        TextInput::make('tahun')
                                            ->validationMessages(['required' => 'Tahun tidak boleh kosong !'])
                                            ->label('Tahun')
                                            ->integer()
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
                                        TextInput::make('issnIsbn')
                                            ->label('ISSN / ISBN')
                                            ->validationMessages(['required' => 'ISSN / ISBN tidak boleh kosong !'])
                                            ->visible(fn(Get $get): bool => $get('tipeDok') === 'Monografi') // Hanya terlihat jika tipeDok adalah Monografi
                                            ->required(fn(Get $get): bool => $get('tipeDok') === 'Monografi'), // Wajib diisi jika tipeDok adalah Monografi
                                        TextInput::make('subjek')
                                            ->label('Subjek')
                                            ->validationMessages(['required' => 'Subjek tidak boleh kosong !'])
                                            ->required(),
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
                                        TextInput::make('bidangHukum')
                                            ->validationMessages(['required' => 'Bidang Hukum tidak boleh kosong !'])
                                            ->label('Bidang Hukum')
                                            ->required(),
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
                            ->columns(1), // Memastikan full-width untuk Tabs
                    ])
                    ->columns(1), // Memastikan full-width untuk Section
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
