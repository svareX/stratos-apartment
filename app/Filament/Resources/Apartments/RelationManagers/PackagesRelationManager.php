<?php

namespace App\Filament\Resources\Apartments\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PackagesRelationManager extends RelationManager
{
    protected static string $relationship = 'packages';

    protected static ?string $recordTitleAttribute = 'name_en';

    public static function getModelLabel(): string
    {
        return __('Package');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Packages');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('price')
                    ->label(__('Price'))
                    ->numeric()
                    ->suffix(__('CZK'))
                    ->required()
                    ->columnSpan(1),
                
                TextInput::make('icon')
                    ->label(__('Icon (Emoji / Text)'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(1),

                Fieldset::make(__('Names'))
                    ->columns(3)
                    ->columnSpan(3)
                    ->schema([
                        TextInput::make('name_en')
                            ->label(__('Name (EN)'))
                            ->required()
                            ->maxLength(255),
                        
                        TextInput::make('name_cs')
                            ->label(__('Name (CS)'))
                            ->maxLength(255),
                            
                        TextInput::make('name_de')
                            ->label(__('Name (DE)'))
                            ->maxLength(255),
                    ]),

                Repeater::make('features')
                    ->label(__('Features'))
                    ->columnSpan('full')
                    ->columns(3)
                    ->schema([
                        TextInput::make('en')
                            ->label(__('Feature (EN)'))
                            ->required(),
                            
                        TextInput::make('cs')
                            ->label(__('Feature (CS)')),
                            
                        TextInput::make('de')
                            ->label(__('Feature (DE)')),
                    ])
                    ->reorderable()
                    ->addActionLabel(__('Add Feature')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('icon')
                    ->label(__('Icon')),
                
                TextColumn::make('name_en')
                    ->label(__('Name (EN)'))
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('price')
                    ->label(__('Price'))
                    ->numeric()
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
