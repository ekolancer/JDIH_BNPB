<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\DokumenHukumResource;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;

class MostViewedDoc extends BaseWidget
{
    protected static ?string $heading = 'Top 5 Dokumen Hukum Dilihat';
    
    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        $model = DokumenHukumResource::getModel();

        return $table
            ->query(
                $model::query()
                    ->with('jenisDokumen:id,nama,singkatan')
                    ->orderByDesc('view_count')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('judul')
                    ->label('Dokumen')
                    ->formatStateUsing(function ($state, $record) {
                        return strtoupper("{$record->jenis_dokumen_singkatan} No. {$record->nomor} Tahun {$record->tahun}");
                    })
                    ->icon(fn($record) => match ($record->tipeDok) {
                        'Peraturan' => 'heroicon-o-scale',
                        'Putusan'    => 'heroicon-o-building-library',
                        default => 'heroicon-o-document-text', // fallback jika tidak cocok
                    })
                    ->wrap()
                    ->alignment(Alignment::Left)
                    ->tooltip(fn($record) => $record->judul),
                TextColumn::make('view_count')
                    ->label('Dilihat')
                    ->numeric()
                    ->alignment(Alignment::Center)
                    ->badge()
                    ->color('success')
                    ->extraAttributes(['class' => 'font-bold']),
            ])
            ->paginated(false);
    }
}
