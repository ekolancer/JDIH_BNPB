<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Visitor;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Enums\VerticalAlignment;
use App\Filament\Resources\VisitorResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VisitorResource\RelationManagers;
use Mohammadhprp\IPToCountryFlagColumn\Columns\IPToCountryFlagColumn;

class VisitorResource extends Resource
{
    protected static ?string $model = Visitor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Features';
    protected static ?int $navigationSort = 2;

    protected static ?string $label = 'Visitor';

    protected static ?string $navigationLabel = 'Visitor'; // Memberikan Label Navigasi

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IPToCountryFlagColumn::make('ip_address')
                    ->label('IP Address')
                    ->location(position: 'below')
                    ->flagPosition('left')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->sortable()
                    ->disableClick(), // Menonaktifkan clickable pada list table
                TextColumn::make('user_agent')
                    ->label('Browser')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->limit(70)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column content exceeds the length limit.
                        return $state;
                    })
                    ->disableClick(), // Menonaktifkan clickable pada list table
                TextColumn::make('visited_at')
                    ->label('Visited_at')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable()
                    ->disableClick(), // Menonaktifkan clickable pada list table
            ])
            ->defaultSort('visited_at', 'desc')
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
            'index' => Pages\ListVisitors::route('/'),
            'create' => Pages\CreateVisitor::route('/create'),
            'edit' => Pages\EditVisitor::route('/{record}/edit'),
        ];
    }
}
