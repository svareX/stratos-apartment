<?php

namespace App\Filament\Resources\Apartments\Schemas;

use App\Enums\ApartmentType;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

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
                                TextInput::make('name_en')
                                    ->label(__('Name (EN)'))
                                    ->placeholder(__('Enter apartment name (EN)'))
                                    ->required()
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(fn ($state, callable $set) => [$set('slug', str()->slug($state)), $set('name', $state)])
                                    ->maxLength(64)
                                    ->columnSpan(1),

                                TextInput::make('name_cs')
                                    ->label(__('Name (CS)'))
                                    ->placeholder(__('Enter apartment name (CS)'))
                                    ->maxLength(64)
                                    ->columnSpan(1),

                                TextInput::make('name_de')
                                    ->label(__('Name (DE)'))
                                    ->placeholder(__('Enter apartment name (DE)'))
                                    ->maxLength(64)
                                    ->columnSpan(1),

                                Hidden::make('name'),

                                TextInput::make('slug')
                                    ->label(__('Slug'))
                                    ->placeholder(__('Enter slug (auto-generated from name)'))
                                    ->disabled(fn ($get) => ! $get('slug_edit'))
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
                                    ->suffix(__('CZK'))
                                    ->numeric()
                                    ->required(),

                                TextInput::make('cleaning_fee')
                                    ->label(__('Cleaning fee'))
                                    ->placeholder(__('Enter cleaning fee (one-time charge)'))
                                    ->suffix(__('CZK'))
                                    ->numeric()
                                    ->required(),

                                TimePicker::make('check_in_time')
                                    ->label(__('Check-in time'))
                                    ->placeholder(__('Select check-in time'))
                                    ->seconds(false)
                                    ->required(),

                                TextInput::make('base_price_eur')
                                    ->label(__('Base price (EUR)'))
                                    ->placeholder(__('Enter base price per night'))
                                    ->suffix(__('€'))
                                    ->columnStart(1)
                                    ->numeric(),

                                TextInput::make('cleaning_fee_eur')
                                    ->label(__('Cleaning fee (EUR)'))
                                    ->placeholder(__('Enter cleaning fee (one-time charge)'))
                                    ->suffix(__('€'))
                                    ->numeric(),

                                TimePicker::make('check_out_time')
                                    ->label(__('Check-out time'))
                                    ->placeholder(__('Select check-out time'))
                                    ->seconds(false)
                                    ->required(),

                                TextInput::make('days_for_cleaning_fee')
                                    ->label(__('Days for cleaning fee'))
                                    ->placeholder(__('Enter number of days for cleaning fee to apply (e.g. 3 means cleaning fee applies for stays of 3 nights or less)'))
                                    ->numeric()
                                    ->columnStart(1)
                                    ->required(),

                                Select::make('type')
                                    ->label(__('Type'))
                                    ->options(ApartmentType::options())
                                    ->default(ApartmentType::Mountains->value)
                                    ->required(),

                                Toggle::make('active')
                                    ->default(true)
                                    ->label(__('Active')),

                                TextInput::make('external_ical_url')
                                    ->label(__('External iCal URL (Booking import)'))
                                    ->url()
                                    ->maxLength(2048)
                                    ->placeholder(__('Paste the private iCal URL from Booking.com')),

                                TextInput::make('ical_export_url')
                                    ->label(__('Our iCal export URL'))
                                    ->hintAction(static fn(Get $get) => Action::make('Copy')->icon('heroicon-s-clipboard')
                                        ->label(__('Copy'))
                                        ->color('primary')
                                        ->tooltip(__('Copy link'))
                                        ->action(static function ($livewire, $state) {
                                            $livewire->js('window.navigator.clipboard.writeText("' . $state . '");');
                                        }))
                                    ->disabled()
                                    ->readOnly()
                                    ->columnSpan(2)
                                    ->formatStateUsing(function ($record): string {
                                        if (! $record?->slug || ! $record?->ical_export_token) {
                                            return __('Export URL will appear after the apartment is saved.');
                                        }

                                        return route('ical.apartment.export', ['apartment' => $record->slug, 'token' => $record->ical_export_token]);
                                    }),
                            ]),

                        Tab::make('Description')
                            ->label(__('Description'))
                            ->schema([
                                Tabs::make('Description')
                                    ->columnSpan('full')
                                    ->tabs([
                                        Tab::make(__('English'))
                                            ->schema([
                                                Textarea::make('description_en')
                                                    ->label(__('Description (EN)'))
                                                    ->placeholder(__('Enter apartment description (EN)'))
                                                    ->maxLength(65535)
                                                    ->rows(6)
                                                    ->afterStateUpdated(fn ($state, callable $set) => $set('description', $state)),
                                            ]),
                                        Tab::make(__('Czech'))
                                            ->schema([
                                                Textarea::make('description_cs')
                                                    ->label(__('Description (CS)'))
                                                    ->placeholder(__('Enter apartment description (CS)'))
                                                    ->maxLength(65535)
                                                    ->rows(6),
                                            ]),
                                        Tab::make(__('German'))
                                            ->schema([
                                                Textarea::make('description_de')
                                                    ->label(__('Description (DE)'))
                                                    ->placeholder(__('Enter apartment description (DE)'))
                                                    ->maxLength(65535)
                                                    ->rows(6),
                                            ]),
                                    ]),
                                Hidden::make('description'),
                            ]),

                        Tab::make('Amenities')
                            ->label(__('Amenities'))
                            ->schema([

                                // Booking.com extra information notice (read-only text)
                                TextEntry::make('booking_com_info')
                                    ->label(__('Extra information'))
                                    ->state(__('This information is being used to feed the SupportBot so that it has more information about the apartments and can help the potential clients more.'))
                                    ->columnSpan('full'),

                                KeyValue::make('amenities')
                                    ->label(__('Amenities (key-value)'))
                                    ->nullable()
                                    ->columnSpan('full'),
                            ]),

                        Tab::make('Tags')
                            ->label(__('Tags'))
                            ->schema([
                                Repeater::make('tags')
                                    ->label(__('Tags'))
                                    ->schema([
                                        TextInput::make('value_en')
                                            ->label(__('Tag (EN)'))
                                            ->placeholder(__('Enter a tag in English'))
                                            ->required()
                                            ->maxLength(50)
                                            ->columnSpan(1),

                                        TextInput::make('value_cs')
                                            ->label(__('Tag (CS)'))
                                            ->placeholder(__('Enter a tag in Czech'))
                                            ->maxLength(50)
                                            ->columnSpan(1),

                                        TextInput::make('value_de')
                                            ->label(__('Tag (DE)'))
                                            ->placeholder(__('Enter a tag in German'))
                                            ->maxLength(50)
                                            ->columnSpan(1),
                                    ])
                                    ->columns(3)
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
                                        FileUpload::make('path')
                                            ->label(__('Photo'))
                                            ->image()
                                            ->required()
                                            ->columnSpan('full'),

                                        Toggle::make('is_new')
                                            ->label(__('Show "New" badge'))
                                            ->default(false)
                                            ->columnSpan('full'),

                                        Tabs::make('Translations')
                                            ->columnSpan('full')
                                            ->tabs([
                                                Tab::make(__('English'))
                                                    ->icon('heroicon-m-language')
                                                    ->schema([
                                                        TextInput::make('title_en')
                                                            ->label(__('Main Title (EN)')),
                                                        TextInput::make('highlighted_title_en')
                                                            ->label(__('Highlighted Title Part (EN)')),
                                                        Textarea::make('description_en')
                                                            ->label(__('Description (EN)'))
                                                            ->rows(3),
                                                    ]),
                                                Tab::make(__('Czech'))
                                                    ->icon('heroicon-m-language')
                                                    ->schema([
                                                        TextInput::make('title_cs')
                                                            ->label(__('Main Title (CS)')),
                                                        TextInput::make('highlighted_title_cs')
                                                            ->label(__('Highlighted Title Part (CS)')),
                                                        Textarea::make('description_cs')
                                                            ->label(__('Description (CS)'))
                                                            ->rows(3),
                                                    ]),
                                                Tab::make(__('German'))
                                                    ->icon('heroicon-m-language')
                                                    ->schema([
                                                        TextInput::make('title_de')
                                                            ->label(__('Main Title (DE)')),
                                                        TextInput::make('highlighted_title_de')
                                                            ->label(__('Highlighted Title Part (DE)')),
                                                        Textarea::make('description_de')
                                                            ->label(__('Description (DE)'))
                                                            ->rows(3),
                                                    ]),
                                            ]),

                                        Hidden::make('is_main')->default(true),
                                        Hidden::make('position'),
                                    ])
                                    ->columns(2)
                                    ->addActionLabel(__('Add main photo')),
                            ]),

                        Tab::make(__('Other photos'))
                            ->schema([
                                Repeater::make('photosOther')
                                    ->label(__('Other photos'))
                                    ->relationship('photosOther')
                                    ->reorderable('position')
                                    ->columns([
                                        'sm' => 1,
                                        'md' => 2,
                                        'lg' => 3,
                                    ])
                                    ->schema([
                                        FileUpload::make('path')
                                            ->label(__('Photo'))
                                            ->image()
                                            ->columnSpan('full')
                                            ->required(),
                                        TextInput::make('tag_en')
                                            ->label(__('Tag (EN)'))
                                            ->maxLength(50)
                                            ->placeholder(__('Enter a tag'))
                                            ->required()
                                            ->columnSpan(1),

                                        TextInput::make('tag_cs')
                                            ->label(__('Tag (CS)'))
                                            ->maxLength(50)
                                            ->placeholder(__('Enter a tag'))
                                            ->columnSpan(1),

                                        TextInput::make('tag_de')
                                            ->label(__('Tag (DE)'))
                                            ->maxLength(50)
                                            ->placeholder(__('Enter a tag'))
                                            ->columnSpan(1),
                                        Hidden::make('is_main')->default(false),
                                        Hidden::make('position'),
                                    ])
                                    ->addActionLabel(__('Add other photo')),
                            ]),
                    ]),
            ]);
    }
}
