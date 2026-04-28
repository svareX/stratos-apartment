<?php

namespace App\Console\Commands;

use App\Models\Apartment;
use App\Models\Hike;
use App\Models\Place;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Services\SitemapGenerator;

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

            try {
                $seo = $apartment->getDynamicSEOData();
                if (! empty($seo->image)) {
                    $tag->addImage($seo->image);
                }
            } catch (\Throwable $_) {
                // ignore apartments without dynamic SEO data
            }

            $sitemap->add($tag);
        }

        foreach (Place::all() as $place) {
            $placeApartment = $place->apartment;
            /** @var \App\Models\Apartment|null $placeApartment */
            if ($placeApartment) {
                $url = route('apartments.show', $placeApartment->slug).'#nearby';
                $tag = Url::create($url)->setLastModificationDate($place->updated_at ?: now());

                try {
                    $seo = $place->getDynamicSEOData();
                    if (! empty($seo->image)) {
                        $tag->addImage($seo->image);
                    }
                } catch (\Throwable $_) {
                    // ignore
                }

                $sitemap->add($tag);
            }
        }

        foreach (Hike::all() as $hike) {
            $hikeApartment = $hike->apartment;
            /** @var \App\Models\Apartment|null $hikeApartment */
            if ($hikeApartment) {
                $url = route('apartments.show', $hikeApartment->slug).'#hikes';
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
