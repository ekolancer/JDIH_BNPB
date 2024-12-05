<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Tables;
use App\Models\Berita;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\Label;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\VerticalAlignment;
use App\Filament\Resources\BeritaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BeritaResource\RelationManagers;
use Filament\Tables\Columns\ToggleColumn;

class BeritaResource extends Resource
{
    protected static ?string $model = Berita::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Informasi Hukum';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $label = 'Berita';

    protected static ?string $navigationLabel = 'Berita'; // Memberikan Label Navigasi


    public static function form(Form $form): Form
    {
        return $form
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
                                ->reactive() // Real-time reactivity
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
                                ->validationMessages(['required' => 'Judul tidak boleh kosong !'])
                                ->disableGrammarly(),
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
                                        ->splitKeys(['Tab', ' '])
                                        ->reorderable()
                                        ->nestedRecursiveRules([
                                            'min:1',
                                            'max:10',
                                        ])
                                        ->required(),
                                ]),
                            Select::make('status')
                                ->options([
                                    'Draft'   => 'Draft',
                                    'Publikasi' => 'Publikasi',
                                ])
                                ->validationMessages(['required' => 'Status tidak boleh kosong !'])
                                ->required(),
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
                TextColumn::make('judul')
                    ->label('Judul')
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('SLUG')
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->disableClick() // Menonaktifkan clickable pada list table
                    ->searchable(),
                TextColumn::make('penulis')
                    ->label('Penulis')
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->searchable(),
                ToggleColumn::make('status')
                    ->label('Status')
                    ->onColor('success') // Hijau untuk "Publikasi"
                    ->offColor('danger') // Merah untuk "Draft"
                    ->beforeStateUpdated(function ($state, $record) {
                        // Konversi nilai boolean menjadi string ENUM
                        $record->status = $state ? 'Publikasi' : 'Draft'; // true → Publikasi, false → Draft
                    })
                    ->afterStateUpdated(function ($state, $record) {
                        // Simpan perubahan ke database setelah konversi
                        $record->save();
                    }),
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
            'index' => Pages\ListBeritas::route('/'),
            'create' => Pages\CreateBerita::route('/create'),
            'edit' => Pages\EditBerita::route('/{record}/edit'),
        ];
    }
}
