<?php

namespace App\Services;

use App\Models\Apartment;
use App\Models\Place;
use App\Models\Hike;
use App\Models\FrequentlyAskedQuestion;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url as SitemapUrl;

class SitemapGenerator
{
    /**
     * Generate a sitemap and write it to the given path (defaults to public/sitemap.xml).
     * Returns the path written.
     */
    public function generate(string $path = null): string
    {
        $path = $path ?? public_path('sitemap.xml');

        try {
            if (! class_exists(Sitemap::class)) {
                Log::warning('Spatie Sitemap not available; falling back to blade sitemap renderer.');
                // Fallback handled by caller; just return target path
                return $path;
            }

            $sitemap = Sitemap::create();

            // Supported locales (keep in sync with SetLocale and LanguageSwitch)
            $locales = ['cs', 'en', 'de'];

            // Static named routes: prefer named routes if available; generate per-locale
            $static = ['about','contact','packages','activities','pricing','reservation','terms','cookies','terms-and-conditions'];

            foreach ($locales as $locale) {
                // Homepage per-locale
                try {
                    $sitemap->add(SitemapUrl::create(route('home', ['locale' => $locale]))->setLastModificationDate(now()));
                } catch (\Throwable $e) {
                    $sitemap->add(SitemapUrl::create(url($locale . '/'))->setLastModificationDate(now()));
                }

                // Static routes
                foreach ($static as $nameOrPath) {
                    if (Route::has($nameOrPath)) {
                        try {
                            $sitemap->add(SitemapUrl::create(route($nameOrPath, ['locale' => $locale])));
                        } catch (\Throwable $e) {
                            $sitemap->add(SitemapUrl::create(url($locale . '/' . ltrim($nameOrPath, '/'))));
                        }
                    } else {
                        $sitemap->add(SitemapUrl::create(url($locale . '/' . ltrim($nameOrPath, '/'))));
                    }
                }

                // Apartments per-locale
                foreach (Apartment::where('active', true)->get() as $apartment) {
                    try {
                        $url = SitemapUrl::create(route('apartments.show', ['locale' => $locale, 'apartment' => $apartment->slug]));
                    } catch (\Throwable $e) {
                        $url = SitemapUrl::create(url($locale . '/apartments/' . $apartment->slug));
                    }

                    if ($apartment->updated_at) {
                        $url->setLastModificationDate($apartment->updated_at);
                    }

                    // Images
                    $photos = $apartment->photos()->orderBy('position')->get();
                    if ($photos->isNotEmpty()) {
                        foreach ($photos as $photo) {
                            if (! empty($photo->path)) {
                                $imageUrl = Storage::url($photo->path);
                                $title = $photo->title_en ?? $photo->title ?? null;
                                $caption = $photo->description_en ?? $photo->description ?? null;
                                $url->addImage($imageUrl, $title, $caption);
                            }
                        }
                    } else {
                        if (method_exists($apartment, 'getDynamicSEOData')) {
                            $seo = $apartment->getDynamicSEOData();
                            if (! empty($seo->image)) {
                                $url->addImage($seo->image);
                            }
                        }
                    }

                    $sitemap->add($url);
                }

                // Places and Hikes (anchor links on apartment pages)
                foreach (Place::all() as $place) {
                    if ($place->apartment) {
                        try {
                            $u = route('apartments.show', ['locale' => $locale, 'apartment' => $place->apartment->slug]) . '#nearby';
                        } catch (\Throwable $e) {
                            $u = url($locale . '/apartments/' . $place->apartment->slug) . '#nearby';
                        }
                        $url = SitemapUrl::create($u);
                        if ($place->updated_at) {
                            $url->setLastModificationDate($place->updated_at);
                        }

                        if (method_exists($place, 'getDynamicSEOData')) {
                            $seo = $place->getDynamicSEOData();
                            if (! empty($seo->image)) {
                                $url->addImage($seo->image);
                            }
                        }

                        $sitemap->add($url);
                    }
                }

                foreach (Hike::all() as $hike) {
                    if ($hike->apartment) {
                        try {
                            $u = route('apartments.show', ['locale' => $locale, 'apartment' => $hike->apartment->slug]) . '#hikes';
                        } catch (\Throwable $e) {
                            $u = url($locale . '/apartments/' . $hike->apartment->slug) . '#hikes';
                        }
                        $url = SitemapUrl::create($u);
                        if ($hike->updated_at) {
                            $url->setLastModificationDate($hike->updated_at);
                        }
                        $sitemap->add($url);
                    }
                }

                // FAQs as standalone pages (if routed) or anchor on relevant page
                foreach (FrequentlyAskedQuestion::where('is_active', true)->get() as $faq) {
                    $sitemap->add(SitemapUrl::create(url($locale . '/'))->setLastModificationDate(now()));
                }
            }

            $sitemap->writeToFile($path);

            return $path;
        } catch (\Throwable $e) {
            Log::error('SitemapGenerator failed: ' . $e->getMessage());
            return $path;
        }
    }
}
