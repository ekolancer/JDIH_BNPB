<?php

namespace App\Filament\Resources;

use App\Models\Visitor;
use Filament\Forms;
use Filament\Tables;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use App\Filament\Resources\VisitorResource\Pages;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

class VisitorResource extends Resource
{
    protected static ?string $model = Visitor::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $pollingInterval = '10s'; // Refresh setiap 10 detik

    protected static \UnitEnum|string|null $navigationGroup = 'Features';

    public static function getNavigationSort(): ?int
    {
        return 3; // atau angka berapapun
    }

    protected static ?string $label = 'Visitor';

    protected static ?string $navigationLabel = 'Visitors Logs';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]); // Tidak diperlukan
    }

    public static function table(Table $table): Table
    {
        return $table
            // 🧩 Kolom Ringan & Optimal
            ->columns([
                TextColumn::make('ip_address')
                    ->label('IP ADDRESS')
                    ->searchable()
                    ->copyable()
                    ->alignment(Alignment::Left)
                    ->disabledClick(),

                TextColumn::make('path')
                    ->label('PATH')
                    ->limit(35)
                    ->alignment(Alignment::Center)
                    ->tooltip(fn($state) => $state)
                    ->searchable()
                    ->disabledClick(),

                TextColumn::make('country')
                    ->label('COUNTRY')
                    ->badge()
                    ->color(fn($state): string => strtolower($state ?? '') === 'indonesia' ? 'success' : 'gray')
                    ->formatStateUsing(fn($state) => ucfirst($state ?? '-'))
                    ->disabledClick(),

                TextColumn::make('city')
                    ->label('CITY')
                    ->alignment(Alignment::Center)
                    ->default('-')
                    ->limit(20)
                    ->disabledClick(),

                IconColumn::make('suspicious')
                    ->label('SUSPICIOUS')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-exclamation')
                    ->alignment(Alignment::Center)
                    ->falseIcon('heroicon-o-check-circle')
                    ->color(fn($state) => $state ? 'danger' : 'success')
                    ->tooltip(fn($state) => $state ? 'Aktivitas mencurigakan' : 'Normal')
                    ->disabledClick(),

                TextColumn::make('visited_at')
                    ->label('VISITED AT')
                    ->dateTime('d M Y H:i')
                    ->alignment(Alignment::Center)
                    ->disabledClick(),
            ])

            // ⚙️ Optimasi Query
            ->defaultSort('visited_at', 'desc')
            ->paginationPageOptions([10, 25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->striped() // Lebih ringan untuk mata

            // 🎛️ Filter ringan
            ->filters([
                SelectFilter::make('country')
                    ->label('Negara')
                    ->options(
                        fn() => Visitor::query()
                            ->select('country')
                            ->distinct()
                            ->orderBy('country')
                            ->pluck('country', 'country')
                            ->filter()
                            ->toArray()
                    ),
                Filter::make('suspicious')
                    ->label('Suspicious Only')
                    ->query(fn($query) => $query->where('suspicious', true)),
            ])

            // 🧭 Aksi
            ->actions([
                \Filament\Actions\ViewAction::make()
                    ->label('Detail')
                    ->modalHeading('Detail Kunjungan')
                    ->modalContent(fn($record) => view('filament.visitors.detail', ['visitor' => $record]))
                    ->icon('heroicon-o-eye'),
            ])

            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih')
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVisitors::route('/'),
        ];
    }
}

