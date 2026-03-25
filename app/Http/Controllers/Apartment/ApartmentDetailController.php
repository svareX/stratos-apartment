<?php

namespace App\Http\Controllers\Apartment;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApartmentDetailController extends Controller
{
    public function __invoke(Request $request)
    {
        $apartment = Apartment::with(['photosMain', 'photosOther'])
            ->where('slug', $request->route('apartment'))
            ->where('active', true)
            ->firstOrFail();

        $heroImages = $apartment->photosMain->map(fn($photo) => Storage::url($photo->path))->toArray();

        if (empty($heroImages)) {
            $heroImages = [
                'https://picsum.photos/1920/1280?random=7',
                'https://picsum.photos/1920/1280?random=8',
                'https://picsum.photos/1920/1280?random=9',
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
            'heroImages' => $heroImages,
            'apartmentImages' => $apartmentImages,
            'galleryPhotos' => $galleryPhotos,
        ]);
    }
}