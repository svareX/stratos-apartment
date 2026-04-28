<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Services\Ical\IcalExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IcalExportController extends Controller
{
    public function __invoke(Request $request, Apartment $apartment, IcalExportService $exportService)
    {
        if (blank($apartment->ical_export_token)) {
            $apartment->forceFill(['ical_export_token' => Str::random(48)])->save();
        }

        $token = (string) $request->query('token', '');

        if ($token === '' || ! hash_equals((string) $apartment->ical_export_token, $token)) {
            abort(403);
        }

        $calendar = $exportService->forApartment($apartment);

        return response($calendar, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'inline; filename="apartment-'.$apartment->id.'.ics"',
        ]);
    }
}
