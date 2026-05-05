<?php

namespace App\Jobs;

use App\Services\BookingReviewsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncReviewsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(BookingReviewsService $service): void
    {
        try {
            $hotelIds = config('services.booking.hotel_ids', null);

            if (empty($hotelIds)) {
                $single = env('BOOKING_HOTEL_ID');
                if (! empty($single)) {
                    $hotelIds = [$single];
                }
            }

            if (empty($hotelIds)) {
                Log::warning('SyncReviewsJob: no hotel IDs configured (services.booking.hotel_ids or BOOKING_HOTEL_ID)');
                return;
            }

            $locales = ['en-gb', 'en-us', 'de', 'cs'];
            foreach ($hotelIds as $hotelId) {
                foreach ($locales as $locale) {
                    $service->import((int) $hotelId, $locale, 'SORT_MOST_RELEVANT', 0);
                }
            }
        } catch (\Throwable $e) {
            Log::error('SyncReviewsJob failed', ['exception' => $e]);
            throw $e;
        }
    }
}
