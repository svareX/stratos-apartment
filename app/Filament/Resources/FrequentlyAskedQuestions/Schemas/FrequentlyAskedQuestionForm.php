<?php

namespace App\Filament\Resources\FrequentlyAskedQuestions\Schemas;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FrequentlyAskedQuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('faq_tabs')
                    ->columnSpan('full')
                    ->tabs([
                        Tab::make(__('Czech'))
                            ->icon('heroicon-m-language')
                            ->schema([
                                TextInput::make('question_cs')
                                    ->label(__('Question (CS)'))
                                    ->required()
                                    ->maxLength(255),
                                MarkdownEditor::make('answer_cs')
                                    ->label(__('Answer (CS)'))
                                    ->required()
                                    ->columnSpan('full'),
                            ]),

                        Tab::make(__('English'))
                            ->icon('heroicon-m-language')
                            ->schema([
                                TextInput::make('question_en')
                                    ->label(__('Question (EN)'))
                                    ->required()
                                    ->maxLength(255),
                                MarkdownEditor::make('answer_en')
                                    ->label(__('Answer (EN)'))
                                    ->required()
                                    ->columnSpan('full'),
                            ]),

                        Tab::make(__('German'))
                            ->icon('heroicon-m-language')
                            ->schema([
                                TextInput::make('question_de')
                                    ->label(__('Question (DE)'))
                                    ->required()
                                    ->maxLength(255),
                                MarkdownEditor::make('answer_de')
                                    ->label(__('Answer (DE)'))
                                    ->required()
                                    ->columnSpan('full'),
                            ]),

                        Tab::make(__('Settings'))
                            ->icon('heroicon-m-cog-6-tooth')
                            ->columns(2)
                            ->schema([
                                TextInput::make('position')
                                    ->label(__('Sort order'))
                                    ->numeric()
                                    ->default(0)
                                    ->helperText(__('Lower numbers appear first')),
                                
                                Toggle::make('is_active')
                                    ->label(__('Is active'))
                                    ->default(true)
                                    ->inline(false),
                            ]),
                    ]),
            ]);
    }
}