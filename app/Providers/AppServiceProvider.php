<?php

namespace App\Providers;

use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Illuminate\Support\ServiceProvider;
use RalphJSmit\Laravel\SEO\Facades\SEOManager;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\MetaTag;
use RalphJSmit\Laravel\SEO\Support\OpenGraphTag;
use RalphJSmit\Laravel\SEO\Support\TwitterCardTag;
use RalphJSmit\Laravel\SEO\Support\LinkTag;
use RalphJSmit\Laravel\SEO\Support\AlternateTag;
use Illuminate\Support\Facades\Route;
use App\Models\ContactSettings;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['cs', 'en', 'de']);
        });

        // Global SEO data transformer: ensure sensible defaults and apply templates
        SEOManager::SEODataTransformer(function (SEOData $SEOData) {
            if (! $SEOData->site_name) {
                $SEOData->site_name = config('seo.site_name') ?? config('app.name');
            }

            if (! $SEOData->favicon) {
                $SEOData->favicon = config('seo.favicon') ?? '/storage/icons/icon.png';
            }

            if (! $SEOData->image && config('seo.image.fallback')) {
                $SEOData->image = config('seo.image.fallback');
            }

            if (! $SEOData->canonical_url) {
                $SEOData->canonical_url = url()->current();
            }

            // Apply title/description templates if configured
            $templates = config('seo.templates', []);
            $siteName = config('seo.site_name') ?? config('app.name');

            $applyTemplate = function (string $template) use ($SEOData, $siteName) {
                $replacements = [
                    '{title}' => $SEOData->title ?? '',
                    '{description}' => $SEOData->description ?? '',
                    '{site_name}' => $siteName,
                    '{url}' => $SEOData->url ?? url()->current(),
                    '{type}' => $SEOData->type ?? '',
                    '{locale}' => $SEOData->locale ?? app()->getLocale(),
                ];

                return trim(preg_replace('/\s+/', ' ', strtr($template, $replacements)));
            };

            $modelType = $SEOData->type ?? null;

            // Title
            $titleTemplate = $templates['title']['models'][$modelType] ?? $templates['title']['default'] ?? null;

            // Homepage fallback title
            $isHomepage = request()->is('/') || trim(url()->current(), '/') === trim(config('app.url'), '/');
            if ($isHomepage && empty($SEOData->title)) {
                $SEOData->title = config('seo.title.homepage_title') ?? ($SEOData->site_name ?? config('app.name'));
            }

            // If no title was set, prefer translated static page titles for common route names
            if (empty($SEOData->title)) {
                try {
                    $routeName = Route::currentRouteName();
                    $routeTitleMap = [
                        'home' => 'Home',
                        'contact' => 'Contact',
                        'about' => 'About the apartment',
                        'packages' => 'Packages',
                        'activities' => 'Activities',
                        'pricing' => 'Pricing',
                        'reservation' => 'Reservation',
                        'terms' => 'Terms and Conditions',
                        'cookies' => 'Cookies',
                    ];

                    if ($routeName && array_key_exists($routeName, $routeTitleMap)) {
                        $SEOData->title = __($routeTitleMap[$routeName]);
                    }
                } catch (\Throwable $e) {
                    // non-fatal: skip translation fallback
                }
            }

            if ($titleTemplate && $SEOData->title) {
                $SEOData->title = $applyTemplate($titleTemplate);
            }

            // Description
            $descTemplate = $templates['description']['models'][$modelType] ?? $templates['description']['default'] ?? null;
            if (empty($SEOData->description) && config('seo.description.fallback')) {
                $SEOData->description = config('seo.description.fallback');
            }

            if ($descTemplate && $SEOData->description) {
                $SEOData->description = $applyTemplate($descTemplate);
            }

            // Apply title suffix if configured and not already present
            $suffix = config('seo.title.suffix');
            if ($suffix && $SEOData->title && ! str_ends_with($SEOData->title, $suffix)) {
                $SEOData->title = $SEOData->title . $suffix;
            }

            // If no structured schema is present, add a basic LodgingBusiness/Organization schema
            if (! $SEOData->schema) {
                try {
                    $contact = ContactSettings::current();
                    $siteUrl = config('app.url');
                    $siteName = $SEOData->site_name ?? config('seo.site_name') ?? config('app.name');

                    $schemas = SchemaCollection::initialize() ?? new SchemaCollection();

                    $schemas->push(function (SEOData $data) use ($contact, $siteName, $siteUrl) {
                        return [
                            '@context' => 'https://schema.org',
                            '@type' => 'LodgingBusiness',
                            'name' => $siteName,
                            'url' => $data->url ?? $siteUrl,
                            'telephone' => $contact?->phone ?? null,
                            'email' => $contact?->email ?? null,
                            'address' => [
                                '@type' => 'PostalAddress',
                                'streetAddress' => $contact?->address ? Str::of($contact->address)->trim()->toString() : null,
                            ],
                            'image' => $data->image ?? null,
                            'sameAs' => collect($contact?->socials ?? [])->map(fn($s) => $s['url'] ?? null)->filter()->values()->all(),
                        ];
                    });

                    // Basic BreadcrumbList JSON-LD (Homepage + current page)
                    $schemas->push(function (SEOData $data) use ($siteUrl) {
                        return [
                            '@context' => 'https://schema.org',
                            '@type' => 'BreadcrumbList',
                            'itemListElement' => [
                                [
                                    '@type' => 'ListItem',
                                    'position' => 1,
                                    'name' => 'Homepage',
                                    'item' => $siteUrl,
                                ],
                                [
                                    '@type' => 'ListItem',
                                    'position' => 2,
                                    'name' => $data->title ?? 'Page',
                                    'item' => $data->url ?? url()->current(),
                                ],
                            ],
                        ];
                    });

                    // If this is an FAQ page, add FAQPage schema
                    if ($modelType === 'faq') {
                        $schemas->push(function (SEOData $d) {
                            return [
                                '@context' => 'https://schema.org',
                                '@type' => 'FAQPage',
                                'mainEntity' => [
                                    [
                                        '@type' => 'Question',
                                        'name' => $d->title ?? null,
                                        'acceptedAnswer' => [
                                            '@type' => 'Answer',
                                            'text' => $d->description ?? null,
                                        ],
                                    ],
                                ],
                            ];
                        });
                    }

                    // If this is an Apartment page, add an apartment-specific schema
                    if ($modelType === 'apartment') {
                        $schemas->push(function (SEOData $data) {
                            try {
                                $apartment = request()->route('apartment');

                                if (! $apartment) {
                                    return null;
                                }

                                $images = [];
                                if (method_exists($apartment, 'photos')) {
                                    foreach ($apartment->photos()->orderBy('position')->get() as $photo) {
                                        if (! empty($photo->path)) {
                                            $images[] = Storage::url($photo->path);
                                        }
                                    }
                                }
                                $images = array_values(array_filter($images));

                                return [
                                    '@context' => 'https://schema.org',
                                    '@type' => 'LodgingBusiness',
                                    'name' => $apartment->name ?? null,
                                    'description' => isset($apartment->description) ? trim(strip_tags((string) $apartment->description)) : null,
                                    'url' => $data->url ?? (isset($apartment->slug) ? route('apartments.show', $apartment->slug) : null),
                                    'image' => $images ?: ($data->image ? [$data->image] : null),
                                    'address' => [
                                        '@type' => 'PostalAddress',
                                        'streetAddress' => $apartment->address ?? null,
                                    ],
                                    'priceRange' => isset($apartment->base_price) ? (string) $apartment->base_price : null,
                                ];
                            } catch (\Throwable $e) {
                                return null;
                            }
                        });
                    }

                    $SEOData->schema = $schemas;
                } catch (\Throwable $e) {
                    // non-fatal: don't break rendering if contact settings unavailable
                }
            }

            // Translate simple static titles when a translation exists.
            // Preserve configured suffix (if any) when translating the base title.
            try {
                if (! empty($SEOData->title)) {
                    $suffix = config('seo.title.suffix');
                    $hadSuffix = false;
                    $base = $SEOData->title;

                    if ($suffix && str_ends_with($base, $suffix)) {
                        $hadSuffix = true;
                        $base = substr($base, 0, -strlen($suffix));
                    }

                    $translated = __($base);
                    if ($translated !== $base) {
                        $SEOData->title = $translated . ($hadSuffix ? $suffix : '');
                    }
                }
            } catch (\Throwable $e) {
                // non-fatal: ignore translation errors
            }

            return $SEOData;
        });

        // Tag transformer: ensure some helpful OG/Twitter tags exist when missing
        SEOManager::tagTransformer(function ($tags) {
            try {
                $siteName = config('seo.site_name') ?? config('app.name');
                $locale = app()->getLocale();

                // og:locale
                $hasOgLocale = $tags->first(fn($t) => $t instanceof OpenGraphTag && $t->collectAttributes()->get('property') === 'og:locale');
                if (! $hasOgLocale) {
                    $tags->push(new OpenGraphTag('locale', $locale));
                }

                // og:site_name
                $hasOgSiteName = $tags->first(fn($t) => $t instanceof OpenGraphTag && $t->collectAttributes()->get('property') === 'og:site_name');
                if (! $hasOgSiteName) {
                    $tags->push(new OpenGraphTag('site_name', $siteName));
                }

                // twitter:site
                $twitter = config('seo.twitter.@username');
                if ($twitter) {
                    $hasTwitter = $tags->first(fn($t) => $t instanceof TwitterCardTag && $t->collectAttributes()->get('name') === 'twitter:site');
                    if (! $hasTwitter) {
                        $tags->push(new TwitterCardTag('site', $twitter));
                    }
                }

                // Ensure og:url exists
                $hasOgUrl = $tags->first(fn($t) => $t instanceof OpenGraphTag && $t->collectAttributes()->get('property') === 'og:url');
                if (! $hasOgUrl) {
                    $tags->push(new OpenGraphTag('url', url()->current()));
                }

                // Ensure og:type exists (prefer product for apartment pages)
                $hasOgType = $tags->first(fn($t) => $t instanceof OpenGraphTag && $t->collectAttributes()->get('property') === 'og:type');
                if (! $hasOgType) {
                    $routeName = \Illuminate\Support\Facades\Route::currentRouteName();
                    $ogType = 'website';
                    if ($routeName === 'apartments.show' || request()->route('apartment')) {
                        $ogType = 'product';
                    }

                    $tags->push(new OpenGraphTag('type', $ogType));
                }

                // Ensure twitter:card exists (prefer large image card when image present)
                $hasTwitterCard = $tags->first(fn($t) => $t instanceof TwitterCardTag && $t->collectAttributes()->get('name') === 'twitter:card');
                if (! $hasTwitterCard) {
                    $hasImage = $tags->first(fn($t) => $t instanceof OpenGraphTag && $t->collectAttributes()->get('property') === 'og:image');
                    $card = $hasImage ? 'summary_large_image' : 'summary';
                    $tags->push(new TwitterCardTag('card', $card));
                }

                // Description meta fallback
                $hasDescription = $tags->first(fn($t) => $t instanceof MetaTag && $t->collectAttributes()->get('name') === 'description');
                if (! $hasDescription && config('seo.description.fallback')) {
                    $tags->push(new MetaTag('description', config('seo.description.fallback')));
                }

                // Robots meta fallback
                $hasRobots = $tags->first(fn($t) => $t instanceof MetaTag && $t->collectAttributes()->get('name') === 'robots');
                $robotsDefault = config('seo.robots.default');
                if (! $hasRobots && $robotsDefault) {
                    $tags->push(new MetaTag('robots', $robotsDefault));
                }

                // Ensure canonical link exists when enabled
                if (config('seo.canonical_link')) {
                    $hasCanonical = $tags->first(fn($t) => $t instanceof LinkTag && $t->collectAttributes()->get('rel') === 'canonical');
                    if (! $hasCanonical) {
                        $tags->push(new LinkTag('canonical', url()->current()));
                    }
                }

                // Hreflang / alternate tags: generate per supported locale
                $locales = ['cs', 'en', 'de'];
                $existingAlternates = $tags->filter(fn($t) => $t instanceof AlternateTag)->map(fn($t) => $t->collectAttributes()->get('hreflang'))->filter()->values()->all();

                foreach ($locales as $l) {
                    if (in_array($l, $existingAlternates)) {
                        continue;
                    }

                    try {
                        $routeName = Route::currentRouteName();
                        $params = optional(Route::current())->parameters() ?: [];

                        if ($routeName) {
                            $params['locale'] = $l;
                            $alternateUrl = route($routeName, $params);
                        } else {
                            // fallback: replace locale prefix in path
                            $path = request()->path();
                            $parts = explode('/', trim($path, '/'));
                            if (isset($parts[0]) && in_array($parts[0], $locales)) {
                                $parts[0] = $l;
                                $alternateUrl = url('/' . implode('/', $parts));
                            } else {
                                $alternateUrl = url('/' . $l . '/' . ltrim($path, '/'));
                            }
                        }

                        $tags->push(new AlternateTag($l, $alternateUrl));
                    } catch (\Throwable $e) {
                        // non-fatal: skip
                    }
                }
            } catch (\Throwable $e) {
                // Non-fatal
            }

            return $tags;
        });
    }
}
