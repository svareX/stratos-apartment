<?php

use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\Apartment\ApartmentDetailController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CookiesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReservationResultController;
use App\Http\Controllers\TermsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Password form routes (not protected by the middleware)
Route::get('/password', function () {
    return view('website-password');
})->name('password.form');

Route::post('/password', function (Request $request) {
    $password = env('WEBSITE_PASSWORD', '');

    if ($password !== '' && $request->input('website_password') === $password) {
        $request->session()->put('website_authenticated', true);

        return redirect()->intended('/');
    }

    return back()->withErrors(['website_password' => 'Invalid password']);
})->name('password.submit');

// The rest of the site's web routes are protected by the website password middleware
Route::middleware([\App\Http\Middleware\RequireWebsitePassword::class])->group(function () {

    Route::get('/', HomeController::class)->name('home');

    Route::get('/apartments/{apartment:slug}', ApartmentDetailController::class)->name('apartments.show');

    Route::get('/contact', ContactController::class)->name('contact');

    Route::get('/about', function () {
        return view('about');
    })->name('about');

    Route::get('/packages', PackagesController::class)->name('packages');
    Route::get('/activities', ActivitiesController::class)->name('activities');

    Route::get('/reservation', ReservationController::class)->name('reservation');
    Route::get('/reservation/result', ReservationResultController::class)->name('reservation.result');

    Route::get('/terms-and-conditions', TermsController::class)->name('terms');
    Route::get('/cookies', CookiesController::class)->name('cookies');

    Route::get('/locale/{locale}', function ($locale) {
        $available = ['en', 'cs', 'de'];
        if (! in_array($locale, $available)) {
            abort(404);
        }

        session(['locale' => $locale]);
        app()->setLocale($locale);

        return redirect()->back();
    })->name('locale.switch');
});
