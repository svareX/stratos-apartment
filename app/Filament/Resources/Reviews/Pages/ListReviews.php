<?php

namespace App\Filament\Resources\Reviews\Pages;

use App\Filament\Resources\Reviews\ReviewResource;
use App\Services\BookingReviewsService;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListReviews extends ListRecords
{
    protected static string $resource = ReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import')
                ->label(__('Import from Booking'))
                ->icon('heroicon-o-arrow-down-tray')
                ->schema([
                    TextInput::make('hotel_id')
                        ->label(__('Hotel ID'))
                        ->required()
                        ->numeric(),
                ])
                ->action(function (array $data, BookingReviewsService $service) {
                    $locales = ['en-gb', 'en-us', 'de', 'cs'];

                    foreach ($locales as $locale) {
                        $service->import((int) $data['hotel_id'], $locale, 'SORT_MOST_RELEVANT', 0);
                    }

                    Notification::make()
                        ->title(__('Reviews imported successfully'))
                        ->success()
                        ->send();
                }),
            Action::make('create')
                ->label(__('Create review'))
                ->icon('heroicon-o-plus')
                ->url(route('filament.admin.resources.reviews.create')),
        ];
    }
}
