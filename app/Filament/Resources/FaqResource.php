<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Filament\Resources\FaqResource\RelationManagers;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Actions\ActionGroup;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationGroup = 'Features';

    protected static ?string $navigationLabel = 'FAQ';

    protected static ?string $pluralLabel = 'FAQ';

    protected static ?string $label = 'FAQ';

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-question-mark-circle';
    }

    public static function getNavigationSort(): ?int
    {
        return 2; // atau angka berapapun
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('question')
                ->label('Pertanyaan')
                ->required()
                ->maxLength(255),

            RichEditor::make('answer')
                ->label('Jawaban')
                ->columnSpanFull(),

            TextInput::make('sort_order')
                ->numeric()
                ->label('Urutan')
                ->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question')
                    ->label('Pertanyaan')
                    ->searchable()
                    ->limit(40)
                    ->alignment(Alignment::Start)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->description(fn (Faq $record): string => str()->limit(strip_tags($record->answer), 60))
                    ->disableClick(), // Menonaktifkan clickable pada list table

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable()
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disableClick(), // Menonaktifkan clickable pada list table

                TextColumn::make('updated_at')
                    ->label('UPDATE')
                    ->timezone('Asia/Jakarta')
                    ->date('j M Y')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disableClick(), // Menonaktifkan clickable pada list table
            ])
            ->reorderable('sort_order') // Drag & Drop sorting
            ->defaultSort('sort_order')
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->modalHeading('Edit FAQ'),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->button()
                    ->label('Aksi'),
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
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
