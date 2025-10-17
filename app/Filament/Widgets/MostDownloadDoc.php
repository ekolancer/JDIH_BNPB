<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\DokumenHukumResource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class MostDownloadDoc extends BaseWidget
{
    protected static ?string $heading = 'Top 5 Dokumen Hukum Diunduh';

    public function table(Table $table): Table
    {
        $model = DokumenHukumResource::getModel();

        return $table
            ->query(
                $model::query()
                    ->orderByDesc('download_count')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('judul')
                    ->label('Dokumen')
                    ->formatStateUsing(function ($state, $record) {
                        return strtoupper("{$record->singkatan} No. {$record->nomor} Tahun {$record->tahun}");
                    })
                    ->icon(fn($record) => match ($record->singkatan) {
                        'Peraturan' => 'heroicon-o-scale',
                        'Putusan'    => 'heroicon-o-building-library',
                        default => 'heroicon-o-document-text', // fallback jika tidak cocok
                    })
                    ->wrap()
                    ->alignment(Alignment::Left)
                    ->tooltip(fn($record) => $record->judul),

                TextColumn::make('download_count')
                    ->label('Unduhan')
                    ->numeric()
                    ->alignment(Alignment::Center)
                    ->badge()
                    ->color('success')
                    ->extraAttributes(['class' => 'font-bold']),
            ])
            ->paginated(false);
    }
}
