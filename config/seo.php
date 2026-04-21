<?php

use RalphJSmit\Laravel\SEO\Models\SEO;

return [
    'model' => SEO::class,
    'site_name' => env('SEO_SITE_NAME', env('APP_NAME')),
    'sitemap' => env('SEO_SITEMAP_PATH', '/sitemap.xml'),
    'canonical_link' => true,
    'robots' => [
        'default' => 'max-snippet:-1,max-image-preview:large,max-video-preview:-1',
        'force_default' => false,
    ],
    'favicon' => env('SEO_FAVICON_PATH', '/images/logo/icon.png'),
    'title' => [
        'infer_title_from_url' => true,
        'suffix' => env('SEO_TITLE_SUFFIX', ' - ' . env('APP_NAME')),
        'homepage_title' => env('SEO_HOMEPAGE_TITLE', null),
    ],
    'description' => [
        'fallback' => env('SEO_DESCRIPTION_FALLBACK', null),
    ],
    'image' => [
        'fallback' => env('SEO_IMAGE_FALLBACK', null),
    ],
    'author' => [
        'fallback' => env('SEO_AUTHOR_FALLBACK', null),
    ],
    'twitter' => [
        '@username' => env('SEO_TWITTER_USERNAME', null),
    ],
    'templates' => [
        'title' => [
            'default' => '{title}',
            'models' => [
                'apartment' => '{title} — {site_name}',
                'hike' => '{title} — Hike near {site_name}',
                'place' => '{title} — {site_name}',
                'faq' => '{title} — {site_name}',
            ],
        ],
        'description' => [
            'default' => '{description}',
            'models' => [
                'apartment' => '{description}',
                'hike' => '{description}',
                'place' => '{description}',
                'faq' => '{description}',
            ],
        ],
    ],
];
