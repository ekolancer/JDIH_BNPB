<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Berhasil Ditambahkan')
                        ->icon('heroicon-o-check-badge')
                        ->iconColor('success')
                        ->body('Data User berhasil ditambahkan.')
                ),
        ];
    }

    public function getTitle(): string
    {
        return "List User";
    }
}
