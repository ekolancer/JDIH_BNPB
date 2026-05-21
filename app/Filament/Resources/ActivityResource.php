<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Builder;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static \UnitEnum|string|null $navigationGroup = 'Systems';

    protected static ?string $navigationLabel = 'Activity Log';

    protected static ?string $label = 'Activity';

    protected static ?string $pluralLabel = 'Activities';

    protected static ?int $navigationSort = 99;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Aktivitas')
                    ->schema([
                        Forms\Components\TextInput::make('log_name')
                            ->label('Log Name'),
                        Forms\Components\TextInput::make('event')
                            ->label('Event'),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('subject_type')
                            ->label('Subject Type'),
                        Forms\Components\TextInput::make('subject_id')
                            ->label('Subject ID'),
                        Forms\Components\TextInput::make('causer_type')
                            ->label('Causer Type'),
                        Forms\Components\TextInput::make('causer_id')
                            ->label('Causer ID'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('log_name')
                    ->label('Log')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'default' => 'gray',
                        default => 'primary',
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('event')
                    ->label('Event')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Subject')
                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '-'),

                Tables\Columns\TextColumn::make('causer.name')
                    ->label('User')
                    ->default('-')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('log_name')
                    ->label('Log Name')
                    ->options(fn () => Activity::distinct()->pluck('log_name', 'log_name')->toArray()),

                SelectFilter::make('event')
                    ->label('Event')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                    ]),

                SelectFilter::make('subject_type')
                    ->label('Subject Type')
                    ->options(fn () => Activity::distinct()
                        ->whereNotNull('subject_type')
                        ->pluck('subject_type', 'subject_type')
                        ->mapWithKeys(fn ($v, $k) => [$k => class_basename($k)])
                        ->toArray()),

                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make()
                    ->label('Detail')
                    ->modalHeading('Detail Activity')
                    ->color('info'),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListActivities::route('/'),
            'view' => Pages\ViewActivity::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
