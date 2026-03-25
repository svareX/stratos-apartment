<?php

namespace App\Http\Controllers\Apartment;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class ApartmentDetailController extends Controller
{
    public function __invoke(Request $request)
    {
        $apartment = Apartment::with(['photosMain', 'photosOther', 'packages'])
            ->where('slug', $request->route('apartment'))
            ->where('active', true)
            ->firstOrFail();

        $locale = App::getLocale();

        $slides = $apartment->photosMain->map(function ($photo) use ($locale) {
            return [
                'image_url' => Storage::url($photo->path),
                'is_new' => $photo->is_new,
                'title' => $photo->{"title_{$locale}"} ?? $photo->title_en ?? '',
                'highlighted_title' => $photo->{"highlighted_title_{$locale}"} ?? $photo->highlighted_title_en ?? '',
                'description' => $photo->{"description_{$locale}"} ?? $photo->description_en ?? '',
            ];
        })->toArray();

        if (empty($slides)) {
            $slides = [
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
                ]
            ];
        }

        $galleryPhotos = $apartment->photosOther;
        $apartmentImages = $galleryPhotos->map(fn($photo) => Storage::url($photo->path))->toArray();

        if (empty($apartmentImages)) {
            $apartmentImages = [
                'https://picsum.photos/1200/900?random=1',
                'https://picsum.photos/1200/900?random=2',
                'https://picsum.photos/1200/900?random=3',
                'https://picsum.photos/1200/900?random=4',
                'https://picsum.photos/1200/900?random=5',
                'https://picsum.photos/1200/900?random=6',
            ];
        }

        return view('apartment.detail')->with([
            'apartment' => $apartment,
            'slides' => $slides,
            'apartmentImages' => $apartmentImages,
            'galleryPhotos' => $galleryPhotos,
        ]);
    }
}