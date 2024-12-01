<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Artikel;
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
use App\Filament\Resources\ArtikelResource\Pages;

class ArtikelResource extends Resource
{
    protected static ?string $model = Artikel::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Dokumen Hukum';
    protected static ?int $navigationSort = 3;

    protected static ?string $label = 'Artikel';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $navigationLabel = 'Artikel'; // Memberikan Label Navigasi

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
                            Forms\Components\TextInput::make('tipeDok')
                                ->required()
                                ->label('Tipe Dokumen')
                                ->default('Artikel Hukum')
                                ->disabled(),
                            Forms\Components\TextInput::make('jenisDok')
                                ->required()
                                ->label('Jenis Artikel')
                                ->validationMessages(['required' => 'Jenis artikel tidak boleh kosong !'])
                                ->maxLength(255),
                            Forms\Components\Textarea::make('judul')
                                ->label('Judul Peraturan')
                                ->rows(5)
                                ->cols(10)
                                ->validationMessages(['required' => 'Judul tidak boleh kosong !'])
                                ->required(),
                            Forms\Components\TextInput::make('slug')
                                ->disabled()
                                ->helperText('Slug dihasilkan otomatis berdasarkan judul'),
                            Forms\Components\TextInput::make('teuBadan')
                                ->required()
                                ->validationMessages(['required' => 'T.E.U Badan tidak boleh kosong !'])
                                ->label('T.E.U Badan')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('penerbit')
                                ->required()
                                ->validationMessages(['required' => 'Penerbit tidak boleh kosong !'])
                                ->label('Penerbit')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('tahunTerbit')
                                ->required()
                                ->validationMessages(['required' => 'Tahun terbit tidak boleh kosong !'])
                                ->label('Tahun')
                                ->Integer(),
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
                            TextInput::make('sumber')
                                ->validationMessages(['required' => 'Sumber Artikel tidak boleh kosong !'])
                                ->label('Sumber Peraturan')
                                ->required(),
                            TextInput::make('subjek')
                                ->validationMessages(['required' => 'Subjek Artikel tidak boleh kosong !'])
                                ->label('Subjek Peraturan')
                                ->required(),
                            TextInput::make('bidangHukum')
                                ->validationMessages(['required' => 'Bidang Hukum tidak boleh kosong !'])
                                ->label('Bidang Hukum')
                                ->required(),
                            TextInput::make('bahasa')
                                ->validationMessages(['required' => 'Bahasa tidak boleh kosong !'])
                                ->label('Basaha')
                                ->required(),
                            TextInput::make('lokasi')
                                ->validationMessages(['required' => 'Lokasi tidak boleh kosong !'])
                                ->label('Lokasi')
                                ->required(),
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
                Tables\Columns\TextColumn::make('jenisDok')
                    ->label('JENIS Artikel')
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->searchable()
                    ->disableClick(), // Menonaktifkan clickable pada list table,
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Artikel')
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->searchable()
                    ->disableClick(), // Menonaktifkan clickable pada list table,
                TextColumn::make('slug')
                    ->label('SLUG')
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->disableClick() // Menonaktifkan clickable pada list table
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahunTerbit')
                    ->label('Tahun Terbit')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disableClick(),
                Tables\Columns\TextColumn::make('penerbit')
                    ->label('Penerbit')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->disableClick(),
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
                        ->modalHeading('Detail Artikel')
                        ->modalContent(fn(Artikel $record): View => view(
                            'filament.pages.action.artikel-view',
                            ['record' => $record]
                        ))
                        ->modalSubmitAction(false)
                        ->color('warning')
                        ->icon('heroicon-o-eye'),
                    Tables\Actions\EditAction::make()
                        ->modalHeading('Edit Data Artikel'),
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
            'index' => Pages\ListArtikels::route('/'),
            'create' => Pages\CreateArtikel::route('/create'),
            'edit' => Pages\EditArtikel::route('/{record}/edit'),
        ];
    }
}
