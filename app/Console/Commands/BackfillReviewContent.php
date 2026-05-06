<?php

namespace App\Console\Commands;

use App\Models\Review;
use Illuminate\Console\Command;

class BackfillReviewContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reviews:backfill-content {--limit=1000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill review content from meta or pros/cons into content_en for existing reviews.';

    public function handle()
    {
        $limit = (int) $this->option('limit');

        $query = Review::query()
            ->whereNull('content_en')
            ->orWhere('content_en', '');

        $count = $query->count();
        $this->info("Found {$count} reviews missing content_en. Processing up to {$limit}...\n");

        $processed = 0;

        $query->limit($limit)->chunk(100, function ($reviews) use (&$processed) {
            /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews */
            foreach ($reviews as $review) {
                /** @var \App\Models\Review $review */
                $meta = $review->meta ?? [];

                $title = $review->title_en ?? $review->title_cs ?? $review->title_de ?? null;
                if (empty($title)) {
                    $title = data_get($meta, 'title') ?? data_get($meta, 'title_translated') ?? data_get($meta, 'review_title') ?? null;
                }

                $content = data_get($meta, 'review_text')
                    ?? data_get($meta, 'review.review')
                    ?? data_get($meta, 'review.text')
                    ?? data_get($meta, 'review.comment')
                    ?? data_get($meta, 'comment')
                    ?? data_get($meta, 'content')
                    ?? data_get($meta, 'text')
                    ?? data_get($meta, 'review_content')
                    ?? null;

                if (empty($content)) {
                    $pros = data_get($meta, 'pros') ?? data_get($meta, 'pros_translated');
                    $cons = data_get($meta, 'cons') ?? data_get($meta, 'cons_translated');
                    $parts = [];
                    if (! empty($pros)) $parts[] = trim($pros);
                    if (! empty($cons)) $parts[] = trim($cons);
                    $content = count($parts) ? implode("\n\n", $parts) : null;
                }

                $did = false;
                if (! empty($content) && empty($review->content_en)) {
                    $review->content_en = is_string($content) ? trim($content) : json_encode($content);
                    $did = true;
                }

                if (! empty($title) && empty($review->title_en)) {
                    $review->title_en = is_string($title) ? trim($title) : json_encode($title);
                    $did = true;
                }

                if ($did) {
                    $review->save();
                    $this->line("Backfilled review {$review->id} (external_id: {$review->external_id})");
                    $processed++;
                }
            }
        });

        $this->info("\nDone. Backfilled {$processed} reviews.");
        return 0;
    }
}
