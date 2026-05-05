<?php

namespace App\Services;

use App\Enums\ReviewSource;
use App\Models\Review;
use Carbon\Carbon;
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

            // Try a few common places the API might return reviews
            $items = data_get($data, 'result.reviews', data_get($data, 'reviews', data_get($data, 'result', [])));

            foreach ($items as $item) {
                $externalId = data_get($item, 'review_id') ?: data_get($item, 'id') ?: data_get($item, 'review.id');
                if (empty($externalId)) {
                    continue;
                }

                $localeShort = substr($locale, 0, 2);

                $title = data_get($item, 'title') ?? data_get($item, 'review_title') ?? data_get($item, 'review.title');

                // Determine language for this item (prefer item's language if present)
                $languageFromItem = data_get($item, 'languagecode') ?? data_get($item, 'language') ?? $locale;
                $languageShort = substr((string) $languageFromItem, 0, 2);

                // Only support these locales for per-locale columns; otherwise default to English
                $supported = ['en', 'cs', 'de'];
                $targetLocaleShort = in_array($languageShort, $supported, true) ? $languageShort : 'en';

                // Try multiple common paths for review text returned by different API shapes
                $content = data_get($item, 'review_text')
                    ?? data_get($item, 'review.review')
                    ?? data_get($item, 'review.text')
                    ?? data_get($item, 'review.comment')
                    ?? data_get($item, 'comment')
                    ?? data_get($item, 'content')
                    ?? data_get($item, 'text')
                    ?? data_get($item, 'review_content')
                    ?? null;

                // If there's no long-form review text, try composing from pros/cons
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

                // If we still don't have content but we do have a title, copy title into content
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

                // Use firstOrNew so we can check existing English fields and avoid overwriting them
                $review = Review::firstOrNew([
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

                // store title/content into the most appropriate locale column (fallback to en)
                $titleKey = "title_{$targetLocaleShort}";
                $contentKey = "content_{$targetLocaleShort}";
                $review->{$titleKey} = $title;
                $review->{$contentKey} = $content;

                // If the imported language is not English, and English columns are empty, copy into English
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