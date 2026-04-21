<?php

namespace App\Jobs;

use App\Services\SitemapGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateSitemap implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $generator = app(SitemapGenerator::class);
            $generator->generate();
        } catch (\Throwable $e) {
            Log::error('GenerateSitemap job failed: '.$e->getMessage());
        }
    }
}
