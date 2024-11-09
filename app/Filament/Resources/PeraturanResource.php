<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Peraturan;
use Filament\Tables\Table;
use Ramsey\Uuid\Type\Integer;
use Filament\Resources\Resource;

use function Laravel\Prompts\select;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Support\Enums\Alignment;
use function Laravel\Prompts\textarea;
use Filament\Forms\Components\Fieldset;
use Illuminate\Support\Facades\Storage;


use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use Filament\Support\Enums\VerticalAlignment;
use App\Filament\Resources\PeraturanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PeraturanResource\RelationManagers;
use Illuminate\Contracts\View\View;

class PeraturanResource extends Resource
{
    protected static ?string $model = Peraturan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Metadata Utama')
                        ->schema([
                            TextInput::make('tipeDok')
                                ->label('Tipe Dokumen')
                                ->required()
                                ->default('Peraturan Perundang - Undangan')
                                ->disabled(),
                            TextInput::make('jenisDok')
                                ->label('Jenis Peraturan')
                                ->required(),
                            TextInput::make('singkatan')
                                ->label('Singkatan Peraturan')
                                ->required(),
                            Textarea::make('judul')
                                ->label('Judul Peraturan')
                                ->rows(5)
                                ->cols(10)
                                ->required(),
                            TextInput::make('teuBadan')
                                ->label('T.E.U Badan')
                                ->required(),
                            TextInput::make('nomor')
                                ->label('Nomor')
                                ->integer()
                                ->required(),
                            TextInput::make('tahun')
                                ->label('Tahun')
                                ->Integer()
                                ->required(),
                            // Fieldset untuk tglPenetapan dan tglPengundangan
                            Fieldset::make('')
                                ->schema([
                                    DatePicker::make('tglPenetapan')
                                        ->label('Tanggal Penetapan Peraturan')
                                        ->format('Y/m/d')
                                        ->required(),
                                    DatePicker::make('tglPengundangan')
                                        ->label('Tanggal Pengundangan Peraturan')
                                        ->format('Y/m/d')
                                        ->required(),
                                    TextInput::make('tempatPenetapan')
                                        ->label('Tempat Penetapan')
                                        ->required()
                                        ->columnSpan(2), // Memperbesar kolom tempatPenetapan agar mengisi ruang,
                                ]),
                            TextInput::make('sumber')
                                ->label('Sumber Peraturan')
                                ->required(),
                            TextInput::make('subjek')
                                ->label('Subjek Peraturan')
                                ->required(),
                            TextInput::make('bidangHukum')
                                ->label('Bidang Hukum')
                                ->required(),
                            TextInput::make('bahasa')
                                ->label('Basaha')
                                ->required(),
                            TextInput::make('lokasi')
                                ->label('Lokasi')
                                ->required(),
                            TextInput::make('penandatangan')
                                ->label('Penandatangan Peraturan')
                                ->required(),
                            Select::make('status')
                                ->options([
                                    'BERLAKU'   => 'BERLAKU',
                                    'TIDAK BERLAKU' => 'TIDAK BERLAKU',
                                ])
                                ->required(),
                        ]),
                    Wizard\Step::make('Metadata Tambahan')
                        ->schema([
                            TextInput::make('urusanPemerintahan')
                                ->label('Urusan Pemerintahan'),
                            TextInput::make('pemrakarsa')
                                ->label('Pemrakarsa'),
                            TextInput::make('peraturanTerkait')
                                ->label('Peraturan Terkait'),
                        ]),
                    Wizard\Step::make('Lampiran')
                        ->schema([
                            FileUpload::make('lampiran')
                                ->disk('public')
                                ->directory('lampiran')
                                ->visibility('public')
                                ->preserveFilenames()
                                ->acceptedFileTypes(['application/pdf'])
                                ->maxSize(10240)
                                ->required(),
                        ]),
                ])
                    ->columnSpan(2) // Menggunakan dua kolom untuk lebih besar
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenisDok')
                    ->label('JENIS PERATURAN')
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->disableClick() // Menonaktifkan clickable pada list table
                    ->searchable(),
                TextColumn::make('judul')
                    ->label('JUDUL PERATURAN')
                    ->alignment(Alignment::Justify)
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
                TextColumn::make('tahun')
                    ->label('TAHUN')
                    ->disableClick() // Menonaktifkan clickable pada list table
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->sortable(),
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
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->sortable(),
                TextColumn::make('lampiran')
                    ->disableClick() // Menonaktifkan clickable pada list table
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->label('LAMPIRAN')
                    ->wrap()
                    ->formatStateUsing(fn($state) => $state
                        ? "<a href='" . Storage::url('/' . $state) . "' target='_blank' class='text-blue-500 underline'>" . basename($state) . "</a>"
                        : 'Tidak ada lampiran')
                    ->html(),
            ])
            ->filters([
                // Filter berdasarkan status menggunakan Select
                // SelectFilter::make('status')
                //     ->options([
                //         'BERLAKU' => 'BERLAKU',
                //         'TIDAK BERLAKU' => 'TIDAK BERLAKU',
                //     ])
                //     ->label('Status Peraturan')
                //     ->query(function ($query, $status) {
                //         // Menambahkan query untuk memfilter berdasarkan status
                //         return $query->where('status', $status);
                //     }),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('detail')
                    ->modalHeading('Detail Peraturan')
                    ->modalWidth('7xl')
                        ->modalContent(fn(Peraturan $record): View => view(
                            'filament.pages.action.peraturan-view',
                            ['record' => $record]
                        ))
                        ->modalSubmitAction(false)
                        ->color('warning')
                        ->icon('heroicon-o-eye'),
                    Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPeraturans::route('/'),
            'create' => Pages\CreatePeraturan::route('/create'),
            // 'edit' => Pages\EditPeraturan::route('/{record}/edit'),
        ];
    }
}
