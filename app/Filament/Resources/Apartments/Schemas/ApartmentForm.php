<?php

namespace App\Filament\Resources\Apartments\Schemas;

use Filament\Schemas\Schema;

class ApartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('name')->label(__('Name'))->required()->maxLength(255),
                \Filament\Forms\Components\Textarea::make('description')->label(__('Description'))->maxLength(65535),
                \Filament\Forms\Components\TextInput::make('address')->label(__('Address'))->required()->maxLength(255),
                \Filament\Forms\Components\TextInput::make('capacity')->label(__('Capacity'))->numeric()->required(),
                \Filament\Forms\Components\KeyValue::make('amenities')->label(__('Amenities (key-value)'))->nullable(),
                \Filament\Forms\Components\FileUpload::make('photos')->label(__('Photos'))->multiple()->image()->nullable(),
                \Filament\Forms\Components\TextInput::make('base_price')->label(__('Base price'))->numeric()->required(),
                \Filament\Forms\Components\Toggle::make('active')->label(__('Active')),
            ]);
    }
}
