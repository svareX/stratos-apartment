<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Services\SitemapGenerator;
use Illuminate\Support\Facades\Log;

class SitemapController
{
    public function __invoke()
    {
        $sitemapPath = public_path('sitemap.xml');

        if (file_exists($sitemapPath) && filesize($sitemapPath) > 0) {
            return response()->file($sitemapPath, ['Content-Type' => 'application/xml']);
        }

        try {
            $generator = app(SitemapGenerator::class);
            $generator->generate($sitemapPath);
        } catch (\Throwable $e) {
            Log::warning('Synchronous sitemap generation failed: '.$e->getMessage());
        }

        if (file_exists($sitemapPath) && filesize($sitemapPath) > 0) {
            return response()->file($sitemapPath, ['Content-Type' => 'application/xml']);
        }

        if (class_exists(\Spatie\Sitemap\Sitemap::class)) {
            try {
                $sitemap = \Spatie\Sitemap\Sitemap::create();

                $sitemap->add(\Spatie\Sitemap\Tags\Url::create(url('/'))->setLastModificationDate(now()));

                $static = ['about', 'contact', 'packages', 'activities', 'pricing', 'reservation', 'terms-and-conditions', 'cookies'];
                foreach ($static as $path) {
                    $sitemap->add(\Spatie\Sitemap\Tags\Url::create(url($path)));
                }

                $apartments = Apartment::where('active', true)->get();
                foreach ($apartments as $apartment) {
                    $url = \Spatie\Sitemap\Tags\Url::create(url('/apartments/'.$apartment->slug));
                    if ($apartment->updated_at) {
                        $url->setLastModificationDate($apartment->updated_at);
                    }
                    $sitemap->add($url);
                }

                $sitemapPath = public_path('sitemap.xml');
                $sitemap->writeToFile($sitemapPath);

                return response()->file($sitemapPath, ['Content-Type' => 'application/xml']);
            } catch (\Throwable $e) {
            }
        }

        $urls = [];

        $urls[] = [
            'loc' => url('/'),
            'lastmod' => now()->toAtomString(),
        ];

        $static = ['about', 'contact', 'packages', 'activities', 'pricing', 'reservation', 'terms-and-conditions', 'cookies'];
        foreach ($static as $path) {
            $urls[] = [
                'loc' => url($path),
            ];
        }

        $apartments = Apartment::where('active', true)->get();
        foreach ($apartments as $apartment) {
            $urls[] = [
                'loc' => url('/apartments/'.$apartment->slug),
                'lastmod' => optional($apartment->updated_at)->toAtomString(),
            ];
        }

        $content = view('sitemap_xml', compact('urls'))->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }
}
