<?php

namespace App\Filament\Resources\Reviews\Schemas;

use App\Enums\ReviewSource;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('review_tabs')
                    ->columnSpan('full')
                    ->tabs([
                        Tab::make(__('English'))
                            ->icon('heroicon-m-language')
                            ->schema([
                                TextInput::make('title_en')
                                    ->label(__('Title (EN)'))
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder(__('Enter title (EN)')),
                                MarkdownEditor::make('content_en')
                                    ->label(__('Content (EN)'))
                                    ->required()
                                    ->columnSpan('full')
                                    ->placeholder(__('Enter content (EN)')),
                            ]),

                        Tab::make(__('Czech'))
                            ->icon('heroicon-m-language')
                            ->schema([
                                TextInput::make('title_cs')
                                    ->label(__('Title (CS)'))
                                    ->maxLength(255)
                                    ->placeholder(__('Enter title (CS)')),
                                MarkdownEditor::make('content_cs')
                                    ->label(__('Content (CS)'))
                                    ->columnSpan('full')
                                    ->placeholder(__('Enter content (CS)')),
                            ]),

                        Tab::make(__('German'))
                            ->icon('heroicon-m-language')
                            ->schema([
                                TextInput::make('title_de')
                                    ->label(__('Title (DE)'))
                                    ->maxLength(255)
                                    ->placeholder(__('Enter title (DE)')),
                                MarkdownEditor::make('content_de')
                                    ->label(__('Content (DE)'))
                                    ->columnSpan('full')
                                    ->placeholder(__('Enter content (DE)')),
                            ]),

                        Tab::make(__('Settings'))
                            ->icon('heroicon-m-cog-6-tooth')
                            ->columns(2)
                            ->schema([
                                TextInput::make('external_id')
                                    ->label(__('External ID'))
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder(__('Enter external ID')),

                                Select::make('source')
                                    ->label(__('Source'))
                                    ->options(ReviewSource::options())
                                    ->required()
                                    ->default(ReviewSource::Local->value),

                                TextInput::make('hotel_id')
                                    ->label(__('Hotel ID'))
                                    ->numeric()
                                    ->placeholder(__('Enter hotel id')),

                                TextInput::make('author_name')
                                    ->label(__('Author'))
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder(__('Enter author name')),

                                TextInput::make('score')
                                    ->label(__('Score'))
                                    ->required()
                                    ->numeric()
                                    ->placeholder(__('Enter score')),
                            ]),
                    ]),
            ]);
    }
}
