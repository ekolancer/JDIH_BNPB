<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Monograf;
use Filament\Tables\Table;
use Filament\Actions\Action;
use PhpParser\Node\Stmt\Label;
use Ramsey\Uuid\Type\Integer;

use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Wizard;
use Filament\Support\Enums\Alignment;


use Illuminate\Support\Facades\Blade;
use function Laravel\Prompts\textarea;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use Filament\Support\Enums\VerticalAlignment;
use Symfony\Contracts\Service\Attribute\Required;
use App\Filament\Resources\MonografResource\Pages;

class MonografResource extends Resource
{
    protected static ?string $model = Monograf::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Dokumen Hukum';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $label = 'Monograf';

    protected static ?string $navigationLabel = 'Monograf'; // Memberikan Label Navigasi

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
                Wizard::make([
                    Wizard\Step::make('Metadata Utama')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->schema([
                            TextInput::make('tipeDok')
                                ->label('Tipe Dokumen')
                                ->required()
                                ->default('Monograf')
                                ->disabled(),
                            TextInput::make('jenisDok')
                                ->label('Jenis Monograf')
                                ->validationMessages(['required' => 'Jenis Monograf tidak boleh kosong !'])
                                ->required(),
                            Textarea::make('judul')
                                ->label('Judul Peraturan')
                                ->rows(5)
                                ->cols(10)
                                ->validationMessages(['required' => 'Judul tidak boleh kosong !'])
                                ->required(),
                            TextInput::make('teuBadan')
                                ->validationMessages(['required' => 'T.E.U Badan tidak boleh kosong !'])
                                ->label('T.E.U Badan')
                                ->required(),
                            Fieldset::make('')
                                ->schema([
                                    DatePicker::make('tglPenetapan')
                                        ->label('Tanggal Penetapan Peraturan')
                                        ->validationMessages(['required' => 'Tanggal Penetapan tidak boleh kosong !'])
                                        ->format('Y/m/d')
                                        ->required(),
                                    DatePicker::make('tglPengundangan')
                                        ->label('Tanggal Pengundangan Peraturan')
                                        ->validationMessages(['required' => 'Tanggal Pengundangan dokumen tidak boleh kosong !'])
                                        ->format('Y/m/d')
                                        ->required(),
                                    TextInput::make('tempatPenetapan')
                                        ->validationMessages(['required' => 'Tempat Penetapan tidak boleh kosong !'])
                                        ->label('Tempat Penetapan')
                                        ->required()
                                        ->columnSpan(2), // Memperbesar kolom tempatPenetapan agar mengisi ruang,
                                ]),
                            Fieldset::make('')
                                ->schema([
                                    Forms\Components\TextInput::make('tahunTerbit')
                                        ->required()
                                        ->validationMessages(['required' => 'Tahun terbit tidak boleh kosong !'])
                                        ->label('Tahun Terbit')
                                        ->Integer(),
                                    Forms\Components\TextInput::make('penerbit')
                                        ->validationMessages(['required' => 'Penerbit tidak boleh kosong !'])
                                        ->label('Penerbit')
                                        ->required(),
                                ])
                        ]),
                    Wizard\Step::make('Metadata Detail')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->schema([
                            Fieldset::make('')
                                ->schema([
                                    Forms\Components\TextInput::make('nomorIndukBuku')
                                        ->validationMessages(['required' => 'Nomor Induk Buku tidak boleh kosong !'])
                                        ->label('Nomor Induk Buku')
                                        ->required(),
                                    Forms\Components\TextInput::make('noPanggil')
                                        ->validationMessages(['required' => 'Nomor Panggil tidak boleh kosong !'])
                                        ->label('Nomor Panggil')
                                        ->required(),
                                    Forms\Components\TextInput::make('isbn')
                                        ->validationMessages(['required' => 'Nomo ISBN / ISSN tidak boleh kosong !'])
                                        ->label('ISBN / ISSN')
                                        ->required(),
                                    Forms\Components\TextInput::make('cetakanEdisi')
                                        ->validationMessages(['required' => 'Cetakan Edisi tidak boleh kosong !'])
                                        ->label('Edisi Cetakan')
                                        ->required(),
                                    Forms\Components\TextInput::make('subjek')
                                        ->validationMessages(['required' => 'Subjek tidak boleh kosong !'])
                                        ->label('Subjek')
                                        ->required(),
                                    Forms\Components\TextInput::make('bahasa')
                                        ->validationMessages(['required' => 'Basaha tidak boleh kosong !'])
                                        ->label('Basaha')
                                        ->required(),
                                    Forms\Components\TextInput::make('lokasi')
                                        ->validationMessages(['required' => 'Lokasi tidak boleh kosong !'])
                                        ->label('Lokasi')
                                        ->required(),
                                    Forms\Components\TextInput::make('bidangHukum')
                                        ->validationMessages(['required' => 'Bidang Hukum tidak boleh kosong !'])
                                        ->label('Bidang Hukum')
                                        ->required(),
                                    Forms\Components\TextInput::make('deskripsiFisik')
                                        ->validationMessages(['required' => 'Deskripsi Fisik tidak boleh kosong !'])
                                        ->label('Deskripsi Fisik')
                                        ->required(),
                                ])
                        ]),
                    Wizard\Step::make('Lampiran')
                        ->schema([
                            FileUpload::make('lampiran')
                                ->disk('public')
                                ->directory('lampiran')
                                ->visibility('public')
                                ->preserveFilenames()
                                ->acceptedFileTypes(['application/pdf'])
                                ->validationMessages(['required' => 'Lampiran tidak boleh kosong !'])
                                ->maxSize(10240)
                                ->required(),
                        ])
                ])
                    ->submitAction(new HtmlString(Blade::render(<<<BLADE
                                    <x-filament::button
                                        type="submit"
                                        size="sm"
                                    >
                                        Submit
                                    </x-filament::button>
                                BLADE)))
                    ->columnSpan(2) // Menggunakan dua kolom untuk lebih besar
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenisDok')
                    ->label('Jenis Monograf')
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->disableClick(), // Menonaktifkan clickable pada list table
                TextColumn::make('judul')
                    ->label('Judul Monograf')
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->searchable(),
                TextColumn::make('tahunTerbit')
                    ->label('Tahun Terbit')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap(),
                TextColumn::make('penerbit')
                    ->label('Penerbit')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cetakanEdisi')
                    ->label('Cetakan Edisi')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap(),
                Tables\Columns\TextColumn::make('isbn')
                    ->label('ISBN / ISSN')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap(),
                Tables\Columns\TextColumn::make('lampiran')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->label('LAMPIRAN')
                    ->wrap()
                    ->formatStateUsing(fn($state) => $state
                        ? "<a href='" . Storage::url('/' . $state) . "' target='_blank' class='text-blue-500 underline'> File Lampiran </a>"
                        : 'Tidak ada lampiran')
                    ->html(),
            ])
            ->filters([
                SelectFilter::make('tahunTerbit')
                    ->label('Tahun')
                    ->options(static::getYearsOptions()),
            ])
            
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('detail')
                        ->modalWidth('5xl')
                        ->modalHeading('Detail Peraturan')
                        ->modalContent(fn(Monograf $record): View => view(
                            'filament.pages.action.monograf-view',
                            ['record' => $record]
                        ))
                        ->modalSubmitAction(false)
                        ->color('warning')
                        ->icon('heroicon-o-eye'),
                    Tables\Actions\EditAction::make()
                        ->modalHeading('Edit Data Monograf'),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->button()
                    ->label('Actions'),
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
            'index' => Pages\ListMonografs::route('/'),
            'create' => Pages\CreateMonograf::route('/create'),
            'edit' => Pages\EditMonograf::route('/{record}/edit'),
        ];
    }
}
