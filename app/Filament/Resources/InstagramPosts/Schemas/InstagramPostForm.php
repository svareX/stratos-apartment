<?php

namespace App\Filament\Resources\InstagramPosts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class InstagramPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('instagram_id')
                    ->label(__('Instagram ID'))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Placeholder::make('image_preview')
                    ->label(__('Image'))
                    ->content(fn ($record) => $record?->image_url ? new HtmlString('<img src="' . $record->image_url . '" style="max-width: 100%; max-height: 400px; border-radius: 8px; object-fit: contain;" />') : ''),

                Hidden::make('image_url'),

                TextInput::make('url')
                    ->label(__('Post URL'))
                    ->required()
                    ->url()
                    ->maxLength(255),

                DateTimePicker::make('posted_at')
                    ->label(__('Posted At'))
                    ->nullable(),

                Textarea::make('caption')
                    ->label(__('Caption'))
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
