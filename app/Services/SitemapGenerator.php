<?php

namespace App\Services;

use App\Models\Apartment;
use App\Models\FrequentlyAskedQuestion;
use App\Models\Hike;
use App\Models\Place;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url as SitemapUrl;

class SitemapGenerator
{
    /**
     * Generate a sitemap and write it to the given path (defaults to public/sitemap.xml).
     * Returns the path written.
     */
    public function generate(?string $path = null): string
    {
        $path = $path ?? public_path('sitemap.xml');

        try {
            if (! class_exists(Sitemap::class)) {
                Log::warning('Spatie Sitemap not available; falling back to blade sitemap renderer.');

                return $path;
            }

            $sitemap = Sitemap::create();

            $locales = ['cs', 'en', 'de'];

            $static = ['about', 'contact', 'packages', 'activities', 'pricing', 'reservation', 'terms', 'cookies', 'terms-and-conditions'];

            foreach ($locales as $locale) {
                try {
                    $sitemap->add(SitemapUrl::create(route('home', ['locale' => $locale]))->setLastModificationDate(now()));
                } catch (\Throwable $e) {
                    $sitemap->add(SitemapUrl::create(url($locale.'/'))->setLastModificationDate(now()));
                }

                foreach ($static as $nameOrPath) {
                    if (Route::has($nameOrPath)) {
                        try {
                            $sitemap->add(SitemapUrl::create(route($nameOrPath, ['locale' => $locale])));
                        } catch (\Throwable $e) {
                            $sitemap->add(SitemapUrl::create(url($locale.'/'.ltrim($nameOrPath, '/'))));
                        }
                    } else {
                        $sitemap->add(SitemapUrl::create(url($locale.'/'.ltrim($nameOrPath, '/'))));
                    }
                }

                Apartment::where('active', true)
                    ->with(['photos' => fn ($q) => $q->orderBy('position')])
                    ->chunkById(100, function ($apartments) use ($sitemap, $locale) {
                        foreach ($apartments as $apartment) {
                            try {
                                try {
                                    $url = SitemapUrl::create(route('apartments.show', ['locale' => $locale, 'apartment' => $apartment->slug]));
                                } catch (\Throwable $e) {
                                    $url = SitemapUrl::create(url($locale.'/apartments/'.$apartment->slug));
                                }

                                if ($apartment->updated_at) {
                                    $url->setLastModificationDate($apartment->updated_at);
                                }

                                if ($apartment->relationLoaded('photos')) {
                                    $photos = collect($apartment->photos)->sortBy('position');
                                } else {
                                    $photos = $apartment->photos()->orderBy('position')->get();
                                }

                                if ($photos->isNotEmpty()) {
                                    foreach ($photos as $photo) {
                                        if (! empty($photo->path)) {
                                            $imageUrl = Storage::url($photo->path);
                                            $title = $photo->title_en ?? $photo->title ?? '';
                                            $caption = $photo->description_en ?? $photo->description ?? '';
                                            try {
                                                $url->addImage($imageUrl, $title, $caption);
                                            } catch (\TypeError $te) {
                                                $url->addImage($imageUrl, (string) $title, (string) $caption);
                                            }
                                        }
                                    }
                                } else {
                                    try {
                                        $seo = $apartment->getDynamicSEOData();
                                        if (! empty($seo->image)) {
                                            try {
                                                $url->addImage($seo->image);
                                            } catch (\TypeError $te) {
                                            }
                                        }
                                    } catch (\Throwable $_) {
                                    }
                                }

                                $sitemap->add($url);
                            } catch (\Throwable $e) {
                                Log::warning('SitemapGenerator: skipping apartment '.$apartment->id.': '.$e->getMessage());

                                continue;
                            }
                        }
                    });

                Place::with('apartment')->chunkById(100, function ($places) use ($sitemap, $locale) {
                    foreach ($places as $place) {
                        $placeApartment = $place->apartment;
                        /** @var \App\Models\Apartment|null $placeApartment */
                        if (! $placeApartment) {
                            continue;
                        }

                        try {
                            try {
                                $u = route('apartments.show', ['locale' => $locale, 'apartment' => $placeApartment->slug]).'#nearby';
                            } catch (\Throwable $e) {
                                $u = url($locale.'/apartments/'.$placeApartment->slug).'#nearby';
                            }
                            $url = SitemapUrl::create($u);
                            if ($place->updated_at) {
                                $url->setLastModificationDate($place->updated_at);
                            }

                            try {
                                $seo = $place->getDynamicSEOData();
                                if (! empty($seo->image)) {
                                    try {
                                        $url->addImage($seo->image);
                                    } catch (\TypeError $te) {
                                    }
                                }
                            } catch (\Throwable $_) {
                            }

                            $sitemap->add($url);
                        } catch (\Throwable $e) {
                            Log::warning('SitemapGenerator: skipping place '.$place->id.': '.$e->getMessage());

                            continue;
                        }
                    }
                });

                Hike::with('apartment')->chunkById(100, function ($hikes) use ($sitemap, $locale) {
                    foreach ($hikes as $hike) {
                        $hikeApartment = $hike->apartment;
                        /** @var \App\Models\Apartment|null $hikeApartment */
                        if (! $hikeApartment) {
                            continue;
                        }

                        try {
                            try {
                                $u = route('apartments.show', ['locale' => $locale, 'apartment' => $hikeApartment->slug]).'#hikes';
                            } catch (\Throwable $e) {
                                $u = url($locale.'/apartments/'.$hikeApartment->slug).'#hikes';
                            }
                            $url = SitemapUrl::create($u);
                            if ($hike->updated_at) {
                                $url->setLastModificationDate($hike->updated_at);
                            }
                            $sitemap->add($url);
                        } catch (\Throwable $e) {
                            Log::warning('SitemapGenerator: skipping hike '.$hike->id.': '.$e->getMessage());

                            continue;
                        }
                    }
                });

                FrequentlyAskedQuestion::where('is_active', true)->chunkById(100, function ($faqs) use ($sitemap, $locale) {
                    foreach ($faqs as $faq) {
                        try {
                            if (! empty($faq->slug)) {
                                try {
                                    $u = route('faq.show', ['locale' => $locale, 'faq' => $faq->slug]);
                                } catch (\Throwable $e) {
                                    $u = url($locale.'/faq/'.$faq->slug);
                                }

                                $url = SitemapUrl::create($u);
                                if (! empty($faq->updated_at)) {
                                    $url->setLastModificationDate($faq->updated_at);
                                }
                                $sitemap->add($url);
                            }
                        } catch (\Throwable $e) {
                            Log::warning('SitemapGenerator: skipping faq '.($faq->id ?? 'n/a').': '.$e->getMessage());

                            continue;
                        }
                    }
                });
            }

            $sitemap->writeToFile($path);

            return $path;
        } catch (\Throwable $e) {
            Log::error('SitemapGenerator failed: '.$e->getMessage());

            return $path;
        }
    }
}
