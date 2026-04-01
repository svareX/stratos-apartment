<?php

namespace App\Filament\Resources\InstagramPosts\Pages;

use App\Filament\Resources\InstagramPosts\InstagramPostResource;
use App\Services\InstagramSyncService;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListInstagramPosts extends ListRecords
{
    protected static string $resource = InstagramPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label(__('Refresh'))
                ->icon('heroicon-o-arrow-path')
                ->schema([
                    TextInput::make('user_id')
                        ->label(__('Instagram User ID'))
                        ->default(config('services.instagram.user_id'))
                        ->disabled()
                        ->hint(__('For Instagram page: @apartmanstratos')),
                    TextInput::make('limit')
                        ->label(__('Number of posts to fetch'))
                        ->numeric()
                        ->default(6)
                        ->required()
                        ->minValue(1)
                        ->maxValue(50),
                ])
                ->action(function (array $data, InstagramSyncService $syncService) {
                    $syncService->sync(null, (int) $data['limit']);

                    Notification::make()
                        ->title(__('Posts refreshed successfully'))
                        ->success()
                        ->send();
                }),
        ];
    }
}
