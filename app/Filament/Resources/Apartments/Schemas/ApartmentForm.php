<?php

namespace App\Filament\Resources\Apartments\Schemas;

use Filament\Schemas\Schema;

class ApartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('name')->required()->maxLength(255),
                \Filament\Forms\Components\Textarea::make('description')->maxLength(65535),
                \Filament\Forms\Components\TextInput::make('address')->required()->maxLength(255),
                \Filament\Forms\Components\TextInput::make('capacity')->numeric()->required(),
                \Filament\Forms\Components\KeyValue::make('amenities')->label('Amenities (key-value)')->nullable(),
                \Filament\Forms\Components\FileUpload::make('photos')->multiple()->image()->nullable(),
                \Filament\Forms\Components\TextInput::make('base_price')->numeric()->required(),
                \Filament\Forms\Components\Toggle::make('active')->label('Active'),
            ]);
    }
}
