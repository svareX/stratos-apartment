<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class RobotsController
{
    public function __invoke()
    {
        $cacheKey = 'robots-txt';
        $ttl = 3600;

        $content = Cache::remember($cacheKey, $ttl, function () {
            $baseUrl = rtrim(config('services.sitemap.base_url', config('app.url')), '/');
            $sitemapLine = 'Sitemap: '.$baseUrl.'/sitemap.xml';

            $unavailableRule = 'Disallow: /*/apartments/*/unavailable';

            $publicPath = public_path('robots.txt');

            if (File::exists($publicPath)) {
                $raw = File::get($publicPath);
                $lines = preg_split('/\r\n|\r|\n/', $raw);
                $foundSitemap = false;
                $foundUnavailable = false;

                foreach ($lines as $i => $line) {
                    if (preg_match('/^\s*Sitemap\s*:/i', $line)) {
                        $foundSitemap = true;
                        if (trim($line) !== $sitemapLine) {
                            $lines[$i] = $sitemapLine;
                        }
                    }
                    if (stripos(trim($line), 'apartments') !== false && stripos(trim($line), 'unavailable') !== false) {
                        $foundUnavailable = true;
                    }
                }

                if (! $foundUnavailable) {
                    if ($foundSitemap) {
                        foreach ($lines as $i => $line) {
                            if (preg_match('/^\s*Sitemap\s*:/i', $line)) {
                                array_splice($lines, $i, 0, $unavailableRule);
                                break;
                            }
                        }
                    } else {
                        $lines[] = '';
                        $lines[] = $unavailableRule;
                    }
                }

                if (! $foundSitemap) {
                    $lines[] = '';
                    $lines[] = $sitemapLine;
                }

                return implode("\n", $lines);
            }

            $rules = [
                'User-agent: *',
                'Disallow: /filament/',
                'Disallow: /api/',
                'Disallow: /vendor/',
                'Disallow: /storage/',
                'Disallow: /horizon/',
                'Disallow: /telescope/',
                'Disallow: /register',
                'Disallow: /login',
                'Disallow: /password',
                $unavailableRule,
                '',
                'Allow: /',
                '',
                $sitemapLine,
            ];

            return implode("\n", $rules);
        });

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
