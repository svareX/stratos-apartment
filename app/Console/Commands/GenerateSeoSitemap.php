<?php

namespace App\Console\Commands;

use App\Models\Apartment;
use App\Models\Hike;
use App\Models\Place;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSeoSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap using models and SEO data';

    public function handle(): int
    {
        $this->info('Generating sitemap...');

        $sitemap = Sitemap::create();

        $sitemap->add(Url::create(url('/'))->setLastModificationDate(now()));

        $staticRoutes = ['about', 'contact', 'packages', 'activities', 'pricing', 'reservation', 'cookies', 'terms'];
        foreach ($staticRoutes as $name) {
            if (Route::has($name)) {
                $sitemap->add(Url::create(route($name))->setLastModificationDate(now()));
            }
        }

        foreach (Apartment::where('active', true)->get() as $apartment) {
            $url = route('apartments.show', $apartment->slug);
            $tag = Url::create($url)->setLastModificationDate($apartment->updated_at);

            if (method_exists($apartment, 'getDynamicSEOData')) {
                $seo = $apartment->getDynamicSEOData();
                if (! empty($seo->image)) {
                    $tag->addImage($seo->image);
                }
            }

            $sitemap->add($tag);
        }

        foreach (Place::all() as $place) {
            if ($place->apartment) {
                $url = route('apartments.show', $place->apartment->slug).'#nearby';
                $tag = Url::create($url)->setLastModificationDate($place->updated_at ?: now());

                if (method_exists($place, 'getDynamicSEOData')) {
                    $seo = $place->getDynamicSEOData();
                    if (! empty($seo->image)) {
                        $tag->addImage($seo->image);
                    }
                }

                $sitemap->add($tag);
            }
        }

        foreach (Hike::all() as $hike) {
            if ($hike->apartment) {
                $url = route('apartments.show', $hike->apartment->slug).'#hikes';
                $tag = Url::create($url)->setLastModificationDate($hike->updated_at ?: now());
                $sitemap->add($tag);
            }
        }

        $generator = app(SitemapGenerator::class);
        $path = $generator->generate();

        $this->info("Sitemap written to {$path}");

        return 0;
    }
}
