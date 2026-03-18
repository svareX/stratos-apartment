<?php

namespace App\Filament\Resources\Apartments\Schemas;

use Filament\Schemas\Schema;

class ApartmentForm
{
    // public static function configure(Schema $schema): Schema
    // {
    //     return $schema
    //         ->components([
    //             \Filament\Forms\Components\Tabs::make('tabs')
    //                 ->tabs([
    //                     \Filament\Forms\Components\Tabs\Tab::make('Main')
    //                         ->schema([
    //                             \Filament\Forms\Components\Grid::make()
    //                                 ->columns(['sm' => 1, 'md' => 2, 'lg' => 3])
    //                                 ->schema([
    //                                     \Filament\Forms\Components\TextInput::make('name')->label(__('Name'))->required()->maxLength(255),

    //                                     // slug + edit button (button toggles a temp state 'slug_edit')
    //                                     \Filament\Forms\Components\TextInput::make('slug')
    //                                         ->label(__('Slug'))
    //                                         ->disabled(fn ($get) => !$get('slug_edit'))
    //                                         ->maxLength(255),

    //                                     \Filament\Forms\Components\Button::make('edit_slug')
    //                                         ->label(__('Edit'))
    //                                         ->dehydrated(false)
    //                                         ->action(fn ($set) => $set('slug_edit', true)),

    //                                     \Filament\Forms\Components\TextInput::make('address')->label(__('Address'))->required()->maxLength(255),
    //                                     \Filament\Forms\Components\TextInput::make('capacity')->label(__('Capacity'))->numeric()->required(),
    //                                     \Filament\Forms\Components\TextInput::make('base_price')->label(__('Base price'))->numeric()->required(),
    //                                     \Filament\Forms\Components\Toggle::make('active')->label(__('Active')),
    //                                     \Filament\Forms\Components\Textarea::make('description')->label(__('Description'))->maxLength(65535)->columnSpan('full'),
    //                                     \Filament\Forms\Components\KeyValue::make('amenities')->label(__('Amenities (key-value)'))->nullable(),

    //                                     // Tags repeater
    //                                     \Filament\Forms\Components\Repeater::make('tags')
    //                                         ->label(__('Tags'))
    //                                         ->schema([
    //                                             \Filament\Forms\Components\TextInput::make('value')
    //                                                 ->label(__('Tag'))
    //                                                 ->required()
    //                                                 ->maxLength(50),
    //                                         ])
    //                                         ->columns(1)
    //                                         ->createItemButtonLabel(__('Add tag')),
    //                                 ]),
    //                         ]),

    //                     \Filament\Forms\Components\Tabs\Tab::make('Main photos')
    //                         ->schema([
    //                             \Filament\Forms\Components\Repeater::make('photos')
    //                                 ->label(__('Main photos'))
    //                                 ->relationship('photos')
    //                                 ->orderable('position')
    //                                 ->schema([
    //                                     \Filament\Forms\Components\FileUpload::make('path')->label(__('Photo'))->image()->required(),
    //                                     \Filament\Forms\Components\TextInput::make('tag')->label(__('Tag'))->maxLength(50),
    //                                     \Filament\Forms\Components\Toggle::make('is_main')->label(__('Is main'))->default(true)->disabled(),
    //                                     \Filament\Forms\Components\Hidden::make('position'),
    //                                 ])
    //                                 ->createItemButtonLabel(__('Add main photo')),
    //                         ]),

    //                     \Filament\Forms\Components\Tabs\Tab::make('Other photos')
    //                         ->schema([
    //                             \Filament\Forms\Components\Repeater::make('photos')
    //                                 ->label(__('Other photos'))
    //                                 ->relationship('photos')
    //                                 ->orderable('position')
    //                                 ->schema([
    //                                     \Filament\Forms\Components\FileUpload::make('path')->label(__('Photo'))->image()->required(),
    //                                     \Filament\Forms\Components\TextInput::make('tag')->label(__('Tag'))->maxLength(50),
    //                                     \Filament\Forms\Components\Toggle::make('is_main')->label(__('Is main'))->default(false)->disabled(),
    //                                     \Filament\Forms\Components\Hidden::make('position'),
    //                                 ])
    //                                 ->createItemButtonLabel(__('Add other photo')),
    //                         ]),
    //                 ]),
    //         ]);
    // }
}
