<?php

namespace App\Filament\Resources\Apartments\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlacesRelationManager extends RelationManager
{
    protected static string $relationship = 'places';

    protected static ?string $recordTitleAttribute = 'name_en';

    public static function getModelLabel(): string
    {
        return __('Place');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Places');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Fieldset::make(__('Media & Location'))
                    ->columns(2)
                    ->columnSpan(3)
                    ->schema([
                        TextInput::make('icon')
                            ->label(__('Icon (Emoji / Text)'))
                            ->maxLength(255)
                            ->columnSpan(1),

                        FileUpload::make('image')
                            ->label(__('Image'))
                            ->image()
                            ->directory('places')
                            ->columnSpan(1),

                        TextInput::make('latitude')
                            ->label(__('Latitude'))
                            ->numeric()
                            ->columnSpan(1),

                        TextInput::make('longitude')
                            ->label(__('Longitude'))
                            ->numeric()
                            ->columnSpan(1),

                        TextInput::make('url')
                            ->label(__('URL Link'))
                            ->url()
                            ->maxLength(255)
                            ->columnSpan('full'),
                    ]),

                Tabs::make('Translations')
                    ->columnSpan('full')
                    ->tabs([
                        Tab::make(__('English'))
                            ->icon('heroicon-m-language')
                            ->schema([
                                TextInput::make('name_en')
                                    ->label(__('Name (EN)'))
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('distance_text_en')
                                    ->label(__('Distance Text (EN)'))
                                    ->placeholder('e.g., 🚶 5 minutes walk')
                                    ->maxLength(255),

                                Textarea::make('description_en')
                                    ->label(__('Description (EN)'))
                                    ->rows(3),
                            ]),

                        Tab::make(__('Czech'))
                            ->icon('heroicon-m-language')
                            ->schema([
                                TextInput::make('name_cs')
                                    ->label(__('Name (CS)'))
                                    ->maxLength(255),

                                TextInput::make('distance_text_cs')
                                    ->label(__('Distance Text (CS)'))
                                    ->maxLength(255),

                                Textarea::make('description_cs')
                                    ->label(__('Description (CS)'))
                                    ->rows(3),
                            ]),

                        Tab::make(__('German'))
                            ->icon('heroicon-m-language')
                            ->schema([
                                TextInput::make('name_de')
                                    ->label(__('Name (DE)'))
                                    ->maxLength(255),

                                TextInput::make('distance_text_de')
                                    ->label(__('Distance Text (DE)'))
                                    ->maxLength(255),

                                Textarea::make('description_de')
                                    ->label(__('Description (DE)'))
                                    ->rows(3),
                            ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('icon')
                    ->label(__('Icon')),

                ImageColumn::make('image')
                    ->label(__('Image'))
                    ->circular(),

                TextColumn::make('name_en')
                    ->label(__('Name (EN)'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('distance_text_en')
                    ->label(__('Distance (EN)')),
            ])
            ->filters([
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
