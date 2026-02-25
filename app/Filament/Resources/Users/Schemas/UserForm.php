<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('name')->required()->maxLength(255),
                \Filament\Forms\Components\TextInput::make('email')->email()->required()->maxLength(255),
                \Filament\Forms\Components\TextInput::make('password')->password()->required()->maxLength(255),
            ]);
    }
}
