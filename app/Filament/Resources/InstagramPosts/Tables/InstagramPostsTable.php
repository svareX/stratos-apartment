<?php

namespace App\Filament\Resources\InstagramPosts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class InstagramPostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('image_url')
                    ->label(__('Image'))
                    ->html()
                    ->formatStateUsing(fn ($state) => $state ? new HtmlString('<img src="'.Storage::url($state).'" referrerpolicy="no-referrer" style="width: 40px; height: 40px; border-radius: 6px; object-fit: cover;" />') : ''),

                TextColumn::make('instagram_id')
                    ->label(__('Instagram ID'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('url')
                    ->label(__('Post URL'))
                    ->url(fn ($record) => $record->url)
                    ->openUrlInNewTab()
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('caption')
                    ->label(__('Caption'))
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('posted_at')
                    ->label(__('Posted At'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
