<?php

namespace App\Filament\Resources\Apartments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Actions\Action;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Tabs;

class ApartmentFormV2
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('tabs')
                    ->columnSpan('full')
                    ->tabs([
                        Tab::make(__('Main'))
                            ->columns([
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 3,
                            ])
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('Name'))
                                    ->placeholder(__('Enter apartment name'))
                                    ->required()
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', str()->slug($state)))
                                    ->maxLength(64),

                                TextInput::make('slug')
                                    ->label(__('Slug'))
                                    ->placeholder(__('Enter slug (auto-generated from name)'))
                                    ->disabled(fn ($get) => !$get('slug_edit'))
                                    ->maxLength(32)
                                    ->suffixAction(
                                        Action::make('edit_slug')
                                        ->label(__('Edit'))
                                        ->icon('heroicon-o-pencil')
                                        ->action(fn ($set) => $set('slug_edit', true))
                                    ),

                                TextInput::make('address')
                                    ->label(__('Address'))
                                    ->placeholder(__('Enter apartment address'))
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('capacity')
                                    ->label(__('Capacity'))
                                    ->placeholder(__('Enter maximum capacity'))
                                    ->numeric()
                                    ->required(),

                                TextInput::make('base_price')
                                    ->label(__('Base price'))
                                    ->placeholder(__('Enter base price per night'))
                                    ->numeric()
                                    ->required(),

                                Toggle::make('active')
                                    ->default(true)
                                    ->label(__('Active')),

                                Textarea::make('description')
                                    ->label(__('Description'))
                                    ->placeholder(__('Enter apartment description'))
                                    ->maxLength(65535)
                                    ->columnSpan('full'),

                                KeyValue::make('amenities')
                                    ->label(__('Amenities (key-value)'))
                                    ->nullable()
                                    ->columnSpan('full'),

                                Repeater::make('tags')
                                    ->label(__('Tags'))
                                    ->schema([
                                        TextInput::make('value')
                                            ->label(__('Tag'))
                                            ->placeholder(__('Enter a tag'))
                                            ->required()
                                            ->maxLength(50),
                                    ])
                                    ->columns(1)
                                    ->addActionLabel(__('Add tag'))
                                    ->columnSpan('full'),
                            ]),

                        Tab::make(__('Main photos'))
                            ->schema([
                                Repeater::make('photosMain')
                                    ->label(__('Main photos'))
                                    ->relationship('photosMain')
                                    ->reorderable('position')
                                    ->schema([
                                        FileUpload::make('path')->label(__('Photo'))->image()->required(),
                                        TextInput::make('tag')->label(__('Tag'))->maxLength(50)->placeholder(__('Enter a tag')),
                                        Hidden::make('is_main')->default(true),
                                        Hidden::make('position'),
                                    ])
                                    ->addActionLabel(__('Add main photo')),
                            ]),

                        Tab::make(__('Other photos'))
                            ->schema([
                                Repeater::make('photosOther')
                                    ->label(__('Other photos'))
                                    ->relationship('photosOther')
                                    ->reorderable('position')
                                    ->schema([
                                        FileUpload::make('path')->label(__('Photo'))->image()->required(),
                                        TextInput::make('tag')->label(__('Tag'))->maxLength(50)->placeholder(__('Enter a tag')),
                                        Hidden::make('is_main')->default(false),
                                        Hidden::make('position'),
                                    ])
                                    ->addActionLabel(__('Add other photo')),
                            ]),
                    ]),
            ]);
    }
}
