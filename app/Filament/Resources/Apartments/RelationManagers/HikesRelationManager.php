<?php

namespace App\Filament\Resources\Apartments\RelationManagers;

use App\Enums\HikeDifficulty;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;


class HikesRelationManager extends RelationManager
{
    protected static string $relationship = 'hikes';

    protected static ?string $recordTitleAttribute = 'name_en';

    public static function getModelLabel(): string
    {
        return __('Hike');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Hikes');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Hikes');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Fieldset::make(__('Hike Details'))
                    ->columns(3)
                    ->columnSpan('full')
                    ->schema([
                        Select::make('difficulty')
                            ->label(__('Difficulty'))
                            ->options(HikeDifficulty::options())
                            ->placeholder(__('Select difficulty'))
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('length')
                            ->label(__('Length (km)'))
                            ->numeric()
                            ->placeholder(__('Enter length in km'))
                            ->required()
                            ->suffix('KM')
                            ->columnSpan(1),

                        Toggle::make('is_for_families')
                            ->label(__('For Families'))
                            ->default(false)
                            ->inline(false)
                            ->columnSpan(1),
                    ]),

                Tabs::make('Translations')
                    ->columnSpan('full')
                    ->tabs([
                        Tab::make(__('English'))
                            ->icon('heroicon-m-language')
                            ->schema([
                                TextInput::make('name_en')
                                    ->label(__('Name (EN)'))
                                    ->placeholder(__('Enter name (EN)'))
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('distance_tx_en')
                                    ->label(__('Distance Text (EN)'))
                                    ->placeholder(__('e.g., ↑ 150 m'))
                                    ->maxLength(255),

                                Textarea::make('description_en')
                                    ->label(__('Description (EN)'))
                                    ->placeholder(__('Enter description (EN)'))
                                    ->rows(3),
                            ]),

                        Tab::make(__('Czech'))
                            ->icon('heroicon-m-language')
                            ->schema([
                                TextInput::make('name_cs')
                                    ->label(__('Name (CS)'))
                                    ->placeholder(__('Enter name (CS)'))
                                    ->maxLength(255),

                                TextInput::make('distance_tx_cs')
                                    ->label(__('Distance Text (CS)'))
                                    ->placeholder(__('e.g., ↑ 150 m'))
                                    ->maxLength(255),

                                Textarea::make('description_cs')
                                    ->label(__('Description (CS)'))
                                    ->placeholder(__('Enter description (CS)'))
                                    ->rows(3),
                            ]),

                        Tab::make(__('German'))
                            ->icon('heroicon-m-language')
                            ->schema([
                                TextInput::make('name_de')
                                    ->label(__('Name (DE)'))
                                    ->placeholder(__('Enter name (DE)'))
                                    ->maxLength(255),

                                TextInput::make('distance_tx_de')
                                    ->label(__('Distance Text (DE)'))
                                    ->placeholder(__('e.g., ↑ 150 m'))
                                    ->maxLength(255),

                                Textarea::make('description_de')
                                    ->label(__('Description (DE)'))
                                    ->placeholder(__('Enter description (DE)'))
                                    ->rows(3),
                            ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name_en')
                    ->label(__('Name (EN)'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('difficulty')
                    ->label(__('Difficulty'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->color(fn ($state) => match ($state) {
                        HikeDifficulty::Easy => 'success',
                        HikeDifficulty::Medium => 'warning',
                        HikeDifficulty::Hard => 'danger',
                        HikeDifficulty::Extreme => 'danger',
                    })
                    ->sortable(),

                TextColumn::make('length')
                    ->label(__('Length (km)'))
                    ->numeric(2)
                    ->suffix(' km')
                    ->sortable(),

                IconColumn::make('is_for_families')
                    ->label(__('For Families'))
                    ->boolean()
                    ->sortable(),
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