<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Hike;
use App\Models\Place;
use App\Models\FrequentlyAskedQuestion;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class SocialPreviewController extends BaseController
{
    /**
     * Return a simple SVG social preview for the given model.
     *
     * URL pattern: /og-image/{type}/{identifier}.svg
     */
    public function __invoke(Request $request, string $type, string $identifier)
    {
        $map = [
            'apartment' => Apartment::class,
            'hike' => Hike::class,
            'place' => Place::class,
            'faq' => FrequentlyAskedQuestion::class,
        ];

        if (! isset($map[$type])) {
            abort(404);
        }

        $modelClass = $map[$type];

        if ($type === 'apartment') {
            $model = $modelClass::where('slug', $identifier)->first();
        } elseif (is_numeric($identifier)) {
            $model = $modelClass::find((int) $identifier);
        } else {
            $model = null;
        }

        if (! $model) {
            abort(404);
        }

        $seoData = null;
        if (method_exists($model, 'getDynamicSEOData')) {
            $seoData = $model->getDynamicSEOData();
        }

        $title = $seoData?->title ?? ($model->name ?? $model->question ?? config('app.name'));
        $subtitle = $seoData?->description ? str(__($seoData->description))->limit(120) : config('app.name');

        $svg = $this->renderSvg((string) $title, (string) $subtitle);

        return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
    }

    protected function renderSvg(string $title, string $subtitle): string
    {
        $titleEsc = htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $subtitleEsc = htmlspecialchars($subtitle, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        $appName = htmlspecialchars(config('app.name'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        return <<<SVG
            <svg xmlns="http://www.w3.org/2000/svg" width="1200" height="630" viewBox="0 0 1200 630" preserveAspectRatio="xMidYMid meet">
            <defs>
                <linearGradient id="g" x1="0" x2="1">
                <stop offset="0" stop-color="#0f172a"/>
                <stop offset="1" stop-color="#0ea5e9"/>
                </linearGradient>
            </defs>
            <rect width="100%" height="100%" fill="url(#g)" />
            <g>
                <text x="60" y="180" font-family="Inter, Arial, sans-serif" font-size="56" font-weight="700" fill="#ffffff">{$titleEsc}</text>
                <text x="60" y="260" font-family="Inter, Arial, sans-serif" font-size="28" fill="#e6f6ff">{$subtitleEsc}</text>
                <text x="60" y="580" font-family="Inter, Arial, sans-serif" font-size="18" fill="#dbeafe">{$appName}</text>
            </g>
            </svg>
        SVG;
    }
}
