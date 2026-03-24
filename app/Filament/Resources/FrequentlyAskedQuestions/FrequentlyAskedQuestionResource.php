<?php

namespace App\Filament\Resources\FrequentlyAskedQuestions;

use App\Filament\Resources\FrequentlyAskedQuestions\Pages\CreateFrequentlyAskedQuestion;
use App\Filament\Resources\FrequentlyAskedQuestions\Pages\EditFrequentlyAskedQuestion;
use App\Filament\Resources\FrequentlyAskedQuestions\Pages\ListFrequentlyAskedQuestions;
use App\Filament\Resources\FrequentlyAskedQuestions\Schemas\FrequentlyAskedQuestionForm;
use App\Filament\Resources\FrequentlyAskedQuestions\Tables\FrequentlyAskedQuestionsTable;
use App\Models\FrequentlyAskedQuestion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FrequentlyAskedQuestionResource extends Resource
{
    protected static ?string $model = FrequentlyAskedQuestion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::QuestionMarkCircle;

    protected static ?string $recordTitleAttribute = 'question';

    public static function getModelLabel(): string
    {
        return __('Frequently Asked Question');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Frequently Asked Questions');
    }

    public static function form(Schema $schema): Schema
    {
        return FrequentlyAskedQuestionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FrequentlyAskedQuestionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFrequentlyAskedQuestions::route('/'),
            'create' => CreateFrequentlyAskedQuestion::route('/create'),
            'edit' => EditFrequentlyAskedQuestion::route('/{record}/edit'),
        ];
    }
}
