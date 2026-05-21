<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Berita;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\Label;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Wizard;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Blade;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\VerticalAlignment;
use App\Filament\Resources\BeritaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BeritaResource\RelationManagers;

class BeritaResource extends Resource
{
    protected static ?string $model = Berita::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-newspaper';

    protected static \UnitEnum|string|null $navigationGroup = 'Main Menu';

    protected static ?int $navigationSort = 3;

    protected static ?string $label = 'Berita';

    protected static ?string $slug = 'berita';

    protected static ?string $navigationLabel = 'Berita'; // Memberikan Label Navigasi

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Metadata Utama')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->schema([
                            Textarea::make('judul')
                                ->label('Judul Berita')
                                ->rows(5)
                                ->cols(10)
                                ->validationMessages(['required' => 'Judul tidak boleh kosong !'])
                                ->required()
                                ->afterStateUpdated(
                                    fn($state, callable $set) =>
                                    $set('slug', Str::slug($state)) // Generate slug dari judul
                                ),
                            TextInput::make('slug')
                                ->disabled()
                                ->helperText('Slug dihasilkan otomatis berdasarkan judul'),
                            RichEditor::make('konten')
                                ->required()
                                ->label('Detail Berita')
                                ->toolbarButtons([
                                    'blockquote',
                                    'bold',
                                    'bulletList',
                                    'codeBlock',
                                    'h2',
                                    'h3',
                                    'italic',
                                    'link',
                                    'orderedList',
                                    'redo',
                                    'strike',
                                    'underline',
                                    'undo',
                                ])
                                ->validationMessages(['required' => 'Judul tidak boleh kosong !']),
                            TextInput::make('penulis')
                                ->validationMessages(['required' => 'Penulis tidak boleh kosong !'])
                                ->label('Penulis')
                                ->required(),
                            Fieldset::make('')
                                ->schema([
                                    TextInput::make('kategori')
                                        ->validationMessages(['required' => 'kategori tidak boleh kosong !'])
                                        ->label('Kategori')
                                        ->required(),
                                    TagsInput::make('tag')
                                        ->validationMessages(['required' => 'Penulis tidak boleh kosong !'])
                                        ->label('Tag')
                                        ->splitKeys(['Tab'])
                                        ->reorderable()
                                        ->nestedRecursiveRules([
                                            'min:1',
                                            'max:10',
                                        ])
                                        ->required(),
                                ]),
                        ]),
                    Wizard\Step::make('Gambar')
                        ->schema([
                            FileUpload::make('gambar')
                                ->disk('public')
                                ->directory('lampiran/berita')
                                ->visibility('public')
                                ->preserveFilenames()
                                ->image()
                                ->validationMessages(['required' => 'Lampiran tidak boleh kosong !'])
                                ->maxSize(10240)
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
                TextColumn::make('judul')
                    ->label('JUDUL')
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->searchable(),
                TextColumn::make('kategori')
                    ->label('KATEGORI')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->disabledClick() // Menonaktifkan clickable pada list table
                    ->searchable(),
                TextColumn::make('tag')
                    ->label('TAG')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ?? 'Tidak ada data')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->searchable(),
                TextColumn::make('penulis')
                    ->label('PENULIS')
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->searchable(),
                ToggleColumn::make('status')
                    ->label('STATUS')
                    ->onColor('success') // Hijau untuk "Publikasi"
                    ->offColor('danger') // Merah untuk "Draft"
                    ->onIcon('heroicon-o-check') // Ikon untuk Published
                    ->offIcon('heroicon-o-pencil') // Ikon untuk Draft
                    ->afterStateUpdated(function ($state, $record) {
                        // Simpan perubahan ke database setelah konversi
                        $record->save();
                    }),
                TextColumn::make('updated_at')
                    ->label('UPDATE')
                    ->timezone('Asia/Jakarta')
                    ->date('j M Y')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disabledClick(), // Menonaktifkan clickable pada list table
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    \Filament\Actions\EditAction::make()
                        ->modalHeading('Edit Berita'),
                    \Filament\Actions\DeleteAction::make(),
                ])
                    ->button()
                    ->label('Aksi')
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBeritas::route('/'),
            'create' => Pages\CreateBerita::route('/create'),
            'edit' => Pages\EditBerita::route('/{record}/edit'),
        ];
    }
}

