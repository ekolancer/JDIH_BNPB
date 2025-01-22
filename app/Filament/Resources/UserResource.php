<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Hash;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\VerticalAlignment;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $label = 'User';

    protected static ?string $navigationLabel = 'User'; // Memberikan Label Navigasi

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Masukan Nama User')
                    ->validationMessages(['required' => 'Nama User tidak boleh kosong !'])
                    ->required(),
                TextInput::make('email')
                    ->label('Masukan Email')
                    ->validationMessages(['required' => 'Email Penggunan tidak boleh kosong !'])
                    ->required(),
                Select::make('roles')
                    ->label('Roles')
                    ->relationship('roles', 'name')
                    ->validationMessages(['required' => 'Roles harus ditentukan !'])
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->rules([
                        'required',
                        'string',
                        'min:8', // Minimal 8 karakter
                        'regex:/[A-Z]/', // Harus ada huruf besar
                        'regex:/[a-z]/', // Harus ada huruf kecil
                        'regex:/[0-9]/', // Harus ada angka
                        'regex:/[\W_]/', // Harus ada simbol (non-alphanumeric)
                    ])
                    ->validationMessages([
                        'required' => 'Password tidak boleh kosong!',
                        'min' => 'Password harus memiliki minimal :min karakter.',
                        'regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol.',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama User')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disableClick(), // Menonaktifkan clickable pada list table
                TextColumn::make('email')
                    ->label('Email User')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disableClick(), // Menonaktifkan clickable pada list table
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->disableClick(), // Menonaktifkan clickable pada list table
                TextColumn::make('updated_at')
                    ->label('Last Update')
                    ->alignment(Alignment::Center)
                    ->verticalAlignment(VerticalAlignment::Center)
                    ->dateTime(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
