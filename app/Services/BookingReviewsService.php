<?php

namespace App\Services;

use App\Enums\ReviewSource;
use App\Models\Review;
use Illuminate\Support\Facades\Http;

class BookingReviewsService
{
    public function import(int $hotelId, string $locale = 'en-gb', string $sortType = 'SORT_MOST_RELEVANT', int $pageNumber = 0): void
    {
        $response = Http::withHeaders([
            'X-RapidAPI-Host' => 'booking-com.p.rapidapi.com',
            'X-RapidAPI-Key' => config('services.booking.rapidapi_key'),
        ])->get('https://booking-com.p.rapidapi.com/v1/hotels/reviews', [
            'hotel_id' => $hotelId,
            'locale' => $locale,
            'sort_type' => $sortType,
            'page_number' => $pageNumber,
        ]);

        if ($response->failed()) {
            logger()->error('Failed to fetch reviews from Booking API', [
                'hotel_id' => $hotelId,
                'locale' => $locale,
                'sort_type' => $sortType,
                'page_number' => $pageNumber,
                'status' => $response->status(),
            ]);

            return;
        }

        if ($response->successful()) {
            $data = $response->json();

            $items = data_get($data, 'result.reviews', data_get($data, 'reviews', data_get($data, 'result', [])));

            // Prefetch existing reviews to avoid repeated queries inside loop
            $externalIds = [];
            foreach ($items as $it) {
                $externalId = data_get($it, 'review_id') ?: data_get($it, 'id') ?: data_get($it, 'review.id');
                if (! empty($externalId)) {
                    $externalIds[] = $externalId;
                }
            }

            $existing = [];
            if (count($externalIds)) {
                $existing = Review::whereIn('external_id', $externalIds)
                    ->where('source', ReviewSource::External)
                    ->get()
                    ->keyBy('external_id')
                    ->all();
            }

            foreach ($items as $item) {
                $externalId = data_get($item, 'review_id') ?: data_get($item, 'id') ?: data_get($item, 'review.id');
                if (empty($externalId)) {
                    continue;
                }

                $localeShort = substr($locale, 0, 2);

                $title = data_get($item, 'title') ?? data_get($item, 'review_title') ?? data_get($item, 'review.title');

                $languageFromItem = data_get($item, 'languagecode') ?? data_get($item, 'language') ?? $locale;
                $languageShort = substr((string) $languageFromItem, 0, 2);

                $supported = ['en', 'cs', 'de'];
                $targetLocaleShort = in_array($languageShort, $supported, true) ? $languageShort : 'en';

                $content = data_get($item, 'review_text')
                    ?? data_get($item, 'review.review')
                    ?? data_get($item, 'review.text')
                    ?? data_get($item, 'review.comment')
                    ?? data_get($item, 'comment')
                    ?? data_get($item, 'content')
                    ?? data_get($item, 'text')
                    ?? data_get($item, 'review_content')
                    ?? null;

                if (empty($content)) {
                    $pros = data_get($item, 'pros') ?? data_get($item, 'pros_translated');
                    $cons = data_get($item, 'cons') ?? data_get($item, 'cons_translated');
                    $parts = [];
                    if (! empty($pros)) {
                        $parts[] = trim($pros);
                    }
                    if (! empty($cons)) {
                        $parts[] = trim($cons);
                    }
                    $content = count($parts) ? implode("\n\n", $parts) : null;
                }

                if (empty($content) && ! empty($title)) {
                    $content = $title;
                }

                if (is_string($content)) {
                    $content = trim($content);
                }
                $author = data_get($item, 'author.name') ?? data_get($item, 'author');
                $average = data_get($item, 'average_score') ?? data_get($item, 'score');
                $customerType = data_get($item, 'customer_type');
                $language = data_get($item, 'language') ?? $locale;
                $reviewedAt = data_get($item, 'review_date') ?? data_get($item, 'created_at') ?? null;

                $review = $existing[$externalId] ?? new Review([
                    'external_id' => $externalId,
                    'source' => ReviewSource::External,
                ]);

                $review->hotel_id = $hotelId;
                $review->author_name = $author;
                $review->locale = $locale;
                $review->language = $language;
                $review->customer_type = $customerType;
                $review->score = $average ? (int) round($average / 4 * 10) : null;
                $review->meta = $item;

                $titleKey = "title_{$targetLocaleShort}";
                $contentKey = "content_{$targetLocaleShort}";
                $review->{$titleKey} = $title;
                $review->{$contentKey} = $content;

                if ($targetLocaleShort !== 'en') {
                    if (empty($review->title_en) && ! empty($title)) {
                        $review->title_en = $title;
                    }
                    if (empty($review->content_en) && ! empty($content)) {
                        $review->content_en = $content;
                    }
                }

                $review->save();
            }
        }
    }
}
