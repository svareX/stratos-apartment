<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\HomepageSettings;
use App\Models\InstagramPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Load the home page.
     */
    public function __invoke(Request $request)
    {
        $apartments = Apartment::where('active', true)->get();

        $settings = HomepageSettings::first();
        $heroSlides = $settings ? ($settings->hero_slides ?? []) : [];
        $locale = App::getLocale();

        $heroSlides = array_map(function ($slide) use ($locale) {
            if (! empty($slide['image'])) {
                $slide['image_url'] = Storage::url($slide['image']);
            }

            $slide['title'] = $slide["title_{$locale}"] ?? $slide['title_en'] ?? '';
            $slide['highlighted_title'] = $slide["highlighted_title_{$locale}"] ?? $slide['highlighted_title_en'] ?? '';
            $slide['description'] = $slide["description_{$locale}"] ?? $slide['description_en'] ?? '';

            return $slide;
        }, $heroSlides);

        if (empty($heroSlides)) {
            $heroSlides = [
                [
                    'image_url' => 'https://picsum.photos/1920/1280?random=7',
                    'is_new' => true,
                    'title' => __('Adventure by day,'),
                    'highlighted_title' => __('wine by night.'),
                    'description' => __('Comfortable accommodation with a view, sauna and private parking.'),
                ],
                [
                    'image_url' => 'https://picsum.photos/1920/1280?random=8',
                    'is_new' => false,
                    'title' => __('Relax and unwind,'),
                    'highlighted_title' => __('in nature.'),
                    'description' => __('Discover the beauty of the mountains right from your window.'),
                ],
            ];
        }

        $apartmentImages = [
            'https://picsum.photos/1200/900?random=1',
            'https://picsum.photos/1200/900?random=2',
            'https://picsum.photos/1200/900?random=3',
            'https://picsum.photos/1200/900?random=4',
            'https://picsum.photos/1200/900?random=5',
            'https://picsum.photos/1200/900?random=6',
        ];

        $galleryImages = $apartments->flatMap(function ($apartment) {
            return $apartment->photosOther;
        });

        $instagramPosts = InstagramPost::orderBy('posted_at', 'desc')->take(6)->get();

        return view('home')->with([
            'apartments' => $apartments,
            'heroSlides' => $heroSlides,
            'apartmentImages' => $apartmentImages,
            'galleryImages' => $galleryImages,
            'instagramPosts' => $instagramPosts,
        ]);
    }
}
