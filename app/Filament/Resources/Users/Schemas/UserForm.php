<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('name')->label(__('Name'))->required()->maxLength(255),
                \Filament\Forms\Components\TextInput::make('email')->label(__('Email'))->email()->required()->maxLength(255),
                \Filament\Forms\Components\TextInput::make('password')->label(__('Password'))->password()->required()->maxLength(255),
            ]);
    }
}