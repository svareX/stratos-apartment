<?php

namespace App\Console\Commands;

use App\Models\Apartment;
use App\Models\Hike;
use App\Models\Place;
use App\Services\SitemapGenerator;
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

        Apartment::where('active', true)
            ->with('photosMain')
            ->chunkById(100, function ($apartments) use ($sitemap) {
                foreach ($apartments as $apartment) {
                    $url = route('apartments.show', $apartment->slug);
                    $tag = Url::create($url)->setLastModificationDate($apartment->updated_at);

                    try {
                        $seo = $apartment->getDynamicSEOData();
                        if (! empty($seo->image)) {
                            $tag->addImage($seo->image);
                        }
                    } catch (\Throwable $_) {
                    }

                    $sitemap->add($tag);
                }
            });

        Place::with('apartment')->chunkById(100, function ($places) use ($sitemap) {
            foreach ($places as $place) {
                $placeApartment = $place->apartment;
                /** @var Apartment|null $placeApartment */
                if ($placeApartment) {
                    $url = route('apartments.show', $placeApartment->slug).'#nearby';
                    $tag = Url::create($url)->setLastModificationDate($place->updated_at ?: now());

                    try {
                        $seo = $place->getDynamicSEOData();
                        if (! empty($seo->image)) {
                            $tag->addImage($seo->image);
                        }
                    } catch (\Throwable $_) {
                    }

                    $sitemap->add($tag);
                }
            }
        });

        Hike::with('apartment')->chunkById(100, function ($hikes) use ($sitemap) {
            foreach ($hikes as $hike) {
                $hikeApartment = $hike->apartment;
                /** @var Apartment|null $hikeApartment */
                if ($hikeApartment) {
                    $url = route('apartments.show', $hikeApartment->slug).'#hikes';
                    $tag = Url::create($url)->setLastModificationDate($hike->updated_at ?: now());
                    $sitemap->add($tag);
                }
            }
        });

        $generator = app(SitemapGenerator::class);
        $path = $generator->generate();

        $this->info("Sitemap written to {$path}");

        return 0;
    }
}
