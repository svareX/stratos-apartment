<?php

use RalphJSmit\Laravel\SEO\Models\SEO;

return [
    'model' => SEO::class,

    /*
     * The site name used for OpenGraph tags. Defaults to APP_NAME.
     */
    'site_name' => env('SEO_SITE_NAME', env('APP_NAME')),

    /*
     * Path to the sitemap. Use a relative path starting with a leading slash.
     */
    'sitemap' => env('SEO_SITEMAP_PATH', '/sitemap.xml'),

    'canonical_link' => true,

    'robots' => [
        'default' => 'max-snippet:-1,max-image-preview:large,max-video-preview:-1',
        'force_default' => false,
    ],

    'favicon' => env('SEO_FAVICON_PATH', '/storage/icons/icon.png'),

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

    /*
     * Title and description templates allow a global pattern to be applied to
     * generated titles and descriptions. Patterns support placeholders:
     * {title}, {description}, {site_name}, {url}, {type}, {locale}
     */
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
