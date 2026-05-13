<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Schemas\Schema;
use App\Models\Infografis;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\Label;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\VerticalAlignment;
use App\Filament\Resources\InfografisResource\Pages;
use App\Filament\Resources\InfografisResource\RelationManagers;
use App\Filament\Resources\InfografisResource\Pages\EditInfografis;
use App\Filament\Resources\InfografisResource\Pages\ListInfografis;
use App\Filament\Resources\InfografisResource\Pages\CreateInfografis;

class InfografisResource extends Resource
{
    protected static ?string $model = Infografis::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-pie';

    protected static \UnitEnum|string|null $navigationGroup = 'Main Menu';

    protected static ?int $navigationSort = 2;

    protected static ?string $label = 'Infografis';

    protected static ?string $navigationLabel = 'Infografis'; // Memberikan Label Navigasi

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Metadata Utama')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->schema([
                            Textarea::make('judul')
                                ->label('Judul')
                                ->rows(5)
                                ->cols(10)
                                ->validationMessages(['required' => 'Judul tidak boleh kosong !'])
                                ->required()
                                // ->reactive() // Real-time reactivity
                                ->afterStateUpdated(
                                    fn($state, callable $set) =>
                                    $set('slug', Str::slug($state)) // Generate slug dari judul
                                ),
                            TextInput::make('slug')
                                ->disabled()
                                ->helperText('Slug dihasilkan otomatis berdasarkan judul'),
                            TagsInput::make('tag')
                                ->validationMessages(['required' => 'Penulis tidak boleh kosong !'])
                                ->label('Tag')
                                ->splitKeys(['Tab'])
                                ->reorderable()
                                ->nestedRecursiveRules([
                                    'min:1',
                                    'max:10',
                                ])
                        ]),
                    Wizard\Step::make('Gambar')
                        ->schema([
                            FileUpload::make('gambar')
                                ->disk('public')
                                ->directory('lampiran/infografis')
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
                ImageColumn::make('gambar')
                    ->label('GAMBAR')
                    ->disk('public')
                    ->visibility('public')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center),
                TextColumn::make('tag')
                    ->label('TAG')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ?? 'Tidak ada data')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->wrap()
                    ->searchable(),
                ToggleColumn::make('status')
                    ->label('DRAFT / PUBLISHED')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->onColor('success') // Hijau untuk "Publikasi"
                    ->offColor('danger') // Merah untuk "Draft"
                    ->onIcon('heroicon-o-check-circle') // Ikon untuk Published
                    ->offIcon('heroicon-o-x-circle') // Ikon untuk Draft
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
                    ->disableClick(), // Menonaktifkan clickable pada list table
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->modalHeading('Edit Infografis'),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->button()
                    ->label('Aksi')
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
            'index' => Pages\ListInfografis::route('/'),
            'create' => Pages\CreateInfografis::route('/create'),
            'edit' => Pages\EditInfografis::route('/{record}/edit'),
        ];
    }
}
