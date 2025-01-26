<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Putusan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Actions\Action;

use Ramsey\Uuid\Type\Integer;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\View\View;
use function Laravel\Prompts\select;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;


use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Blade;
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
use App\Filament\Resources\PutusanResource\Pages;
use Symfony\Contracts\Service\Attribute\Required;

class PutusanResource extends Resource
{
    protected static ?string $model = Putusan::class;

    public static function shouldRegisterNavigation(): bool
    {
        return false; //menyembunyikan menu navigasi
    }

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = 'Dokumen Hukum';

    protected static ?int $navigationSort = 4;

    protected static ?string $label = 'Putusan';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $navigationLabel = 'Putusan'; // Memberikan Label Navigasi

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
                                ->default('Putusan')
                                ->disabled(),
                            TextInput::make('jenisDok')
                                ->label('Jenis Putusan')
                                ->validationMessages(['required' => 'Jenis Putusan tidak boleh kosong !'])
                                ->required(),
                            TextInput::make('singkatan')
                                ->label('Singkatan Putusan')
                                ->validationMessages(['required' => 'Singkatan tidak boleh kosong !'])
                                ->required(),
                            Textarea::make('judul')
                                ->label('Judul Putusan')
                                ->rows(5)
                                ->cols(10)
                                ->validationMessages(['required' => 'Judul tidak boleh kosong !'])
                                ->required()
                                ->reactive() // Real-time reactivity
                                ->afterStateUpdated(
                                    fn($state, callable $set) =>
                                    $set('slug', Str::slug($state)) // Generate slug dari judul
                                ),
                            TextInput::make('slug')
                                ->disabled()
                                ->helperText('Slug dihasilkan otomatis berdasarkan judul'),
                            TextInput::make('teuBadan')
                                ->validationMessages(['required' => 'T.E.U Badan tidak boleh kosong !'])
                                ->label('T.E.U Badan')
                                ->required(),
                            TextInput::make('nomor')
                                ->validationMessages(['required' => 'Nomor tidak boleh kosong !'])
                                ->label('Nomor')
                                ->integer()
                                ->required(),
                            // Fieldset untuk tglPenetapan dan tglPengundangan
                            Fieldset::make('')
                                ->schema([
                                    DatePicker::make('tglPenetapan')
                                        ->label('Tanggal Penetapan Putusan')
                                        ->validationMessages(['required' => 'Tanggal Penetapan tidak boleh kosong !'])
                                        ->format('Y/m/d')
                                        ->required(),
                                    DatePicker::make('tglPengundangan')
                                        ->label('Tanggal Pengundangan Putusan')
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
                                    TextInput::make('tahunTerbit')
                                        ->required()
                                        ->validationMessages(['required' => 'Tahun terbit tidak boleh kosong !'])
                                        ->label('Tahun Terbit')
                                        ->Integer(),
                                    Forms\Components\TextInput::make('penerbit')
                                        ->validationMessages(['required' => 'Penerbit tidak boleh kosong !'])
                                        ->label('Penerbit')
                                        ->required(),
                                ]),
                            TextInput::make('sumber')
                                ->validationMessages(['required' => 'Sumber dokumen tidak boleh kosong !'])
                                ->label('Sumber Putusan')
                                ->required(),
                            TextInput::make('subjek')
                                ->validationMessages(['required' => 'Subjek dokumen tidak boleh kosong !'])
                                ->label('Subjek Putusan')
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
                            TextInput::make('penandatangan')
                                ->validationMessages(['required' => 'Penandatangan tidak boleh kosong !'])
                                ->label('Penandatangan Putusan')
                                ->required(),
                            Select::make('status')
                                ->options([
                                    'BERLAKU'   => 'BERLAKU',
                                    'TIDAK BERLAKU' => 'TIDAK BERLAKU',
                                ])
                                ->validationMessages(['required' => 'Status tidak boleh kosong !'])
                                ->required(),
                        ]),
                    Wizard\Step::make('Metadata Tambahan')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->schema([
                            TextInput::make('pemrakarsa')
                                ->label('Pemrakarsa'),
                            TextInput::make('PutusanTerkait')
                                ->label('Putusan Terkait'),

                        ]),
                    Wizard\Step::make('Lampiran')
                        ->schema([
                            FileUpload::make('lampiran')
                                ->disk('public')
                                ->directory('lampiran/putusan')
                                ->visibility('public')
                                ->preserveFilenames()
                                ->acceptedFileTypes(['application/pdf'])
                                ->validationMessages(['required' => 'Lampiran tidak boleh kosong !'])
                                ->maxSize(25600)
                                ->required(),
                        ])
                ])
                    ->submitAction(new HtmlString(Blade::render(<<<BLADE
                <x-filament::button
                    type="submit"
                    size="sm"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>Submit</span>
                    <x-filament::loading-indicator wire:loading class="h-5 w-5" />
                </x-filament::button>
            BLADE)))
                    ->columnSpan(3) // Menggunakan dua kolom untuk lebih besar
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenisDok')
                    ->label('JENIS PUTUSAN')
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->disableClick(), // Menonaktifkan clickable pada list table
                TextColumn::make('judul')
                    ->label('JUDUL PUTUSAN')
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->disableClick() // Menonaktifkan clickable pada list table
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('SLUG')
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->disableClick() // Menonaktifkan clickable pada list table
                    ->searchable(),
                TextColumn::make('nomor')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->label('NOMOR')
                    ->disableClick() // Menonaktifkan clickable pada list table
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center),
                TextColumn::make('tahunTerbit')
                    ->label('TAHUN')
                    ->disableClick() // Menonaktifkan clickable pada list table
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center),
                TextColumn::make('status')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disableClick() // Menonaktifkan clickable pada list table
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'BERLAKU' => 'success',
                        'TIDAK BERLAKU' => 'danger',
                    })
                    ->label('STATUS')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center),
                TextColumn::make('view_count')
                    ->label('DILIHAT')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disableClick(), // Menonaktifkan clickable pada list table
                TextColumn::make('download_count')
                    ->label('DIUNDUH')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disableClick(), // Menonaktifkan clickable pada list table
                TextColumn::make('lampiran')
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
                // Filter berdasarkan status menggunakan Select
                SelectFilter::make('status')
                    ->options([
                        'BERLAKU' => 'BERLAKU',
                        'TIDAK BERLAKU' => 'TIDAK BERLAKU',
                    ])
                    ->label('Status Peraturan'),

                SelectFilter::make('tahun')
                    ->label('Tahun')
                    ->options(static::getYearsOptions()),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('detail')
                        ->modalWidth('5xl')
                        ->modalHeading('Detail Putusan')
                        ->modalContent(fn(Putusan $record): View => view(
                            'filament.pages.action.putusan-view',
                            ['record' => $record]
                        ))
                        ->modalSubmitAction(false)
                        ->color('warning')
                        ->icon('heroicon-o-eye'),
                    Tables\Actions\EditAction::make()
                        ->modalHeading('Edit Data Peraturan'),
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
            'index' => Pages\ListPutusans::route('/'),
            'create' => Pages\CreatePutusan::route('/create'),
            'edit' => Pages\EditPutusan::route('/{record}/edit'),
        ];
    }
}
