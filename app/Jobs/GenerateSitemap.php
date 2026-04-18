<?php

namespace App\Jobs;

use App\Models\Apartment;
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
        $sitemapPath = public_path('sitemap.xml');
        $domain = config('services.sitemap.base_url', config('app.url'));

        // Try crawler-based generation if enabled
        if (class_exists(\Spatie\Sitemap\SitemapGenerator::class) && config('services.sitemap.use_crawler', false)) {
            try {
                $sitemap = \Spatie\Sitemap\SitemapGenerator::create($domain)->getSitemap();

                if (class_exists(\Spatie\Sitemap\Tags\Url::class)) {
                    $apartments = Apartment::where('active', true)->get();
                    foreach ($apartments as $apartment) {
                        $url = \Spatie\Sitemap\Tags\Url::create(url('/apartments/' . $apartment->slug));
                        if ($apartment->updated_at) {
                            $url->setLastModificationDate($apartment->updated_at);
                        }
                        $sitemap->add($url);
                    }
                }

                $sitemap->writeToFile($sitemapPath);
                return;
            } catch (\Throwable $e) {
                Log::warning('Sitemap crawler generation failed: ' . $e->getMessage());
                // fall through to programmatic generation
            }
        }

        // Programmatic generation using Spatie's Sitemap builder (no crawling)
        if (class_exists(\Spatie\Sitemap\Sitemap::class) && class_exists(\Spatie\Sitemap\Tags\Url::class)) {
            try {
                $sitemap = \Spatie\Sitemap\Sitemap::create();
                $sitemap->add(\Spatie\Sitemap\Tags\Url::create(url('/'))->setLastModificationDate(now()));

                $static = ['about','contact','packages','activities','pricing','reservation','terms-and-conditions','cookies'];
                foreach ($static as $path) {
                    $sitemap->add(\Spatie\Sitemap\Tags\Url::create(url($path)));
                }

                $apartments = Apartment::where('active', true)->get();
                foreach ($apartments as $apartment) {
                    $url = \Spatie\Sitemap\Tags\Url::create(url('/apartments/' . $apartment->slug));
                    if ($apartment->updated_at) {
                        $url->setLastModificationDate($apartment->updated_at);
                    }
                    $sitemap->add($url);
                }

                $sitemap->writeToFile($sitemapPath);
                return;
            } catch (\Throwable $e) {
                Log::warning('Sitemap programmatic generation failed: ' . $e->getMessage());
            }
        }

        // Final fallback: render the blade sitemap view and write the file
        try {
            $urls = [];
            $urls[] = ['loc' => url('/'), 'lastmod' => now()->toAtomString()];

            $static = ['about','contact','packages','activities','pricing','reservation','terms-and-conditions','cookies'];
            foreach ($static as $path) {
                $urls[] = ['loc' => url($path)];
            }

            $apartments = Apartment::where('active', true)->get();
            foreach ($apartments as $apartment) {
                $urls[] = [
                    'loc' => url('/apartments/' . $apartment->slug),
                    'lastmod' => optional($apartment->updated_at)->toAtomString(),
                ];
            }

            $content = view('sitemap_xml', compact('urls'))->render();
            file_put_contents($sitemapPath, $content);
        } catch (\Throwable $e) {
            Log::error('Failed to write sitemap file: ' . $e->getMessage());
        }
    }
}
