<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\HomepageSettings;
use Illuminate\Http\Request;
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

        $heroImages = !empty($settings?->hero_images) 
            ? collect($settings->hero_images)->map(fn($path) => Storage::url($path))->toArray()
            : [
                'https://picsum.photos/1200/900?random=7',
                'https://picsum.photos/1200/900?random=8',
                'https://picsum.photos/1200/900?random=9',
            ];

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

        return view('home')->with([
            'apartments' => $apartments,
            'heroImages' => $heroImages,
            'apartmentImages' => $apartmentImages,
            'galleryImages' => $galleryImages,
        ]);
    }
}
