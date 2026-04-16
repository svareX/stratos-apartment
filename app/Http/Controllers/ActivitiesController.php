<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Contracts\View\View;

class ActivitiesController extends Controller
{
    public function __invoke(): View
    {
        $apartments = Apartment::query()
            ->where('active', true)
            ->with(['hikes', 'places', 'photosMain'])
            ->get();

        return view('activities', [
            'apartments' => $apartments,
        ]);
    }
}
