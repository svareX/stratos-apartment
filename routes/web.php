<?php

use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\Apartment\ApartmentDetailController;
use App\Http\Controllers\Apartment\ApartmentUnavailableController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CookiesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IcalExportController;
use App\Http\Controllers\ImageProxyController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReservationResultController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SocialPreviewController;
use App\Http\Controllers\TermsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



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

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/robots.txt', RobotsController::class)->name('robots');

Route::get('/ical/apartments/{apartment}/reservations.ics', IcalExportController::class)->name('ical.apartment.export');

Route::get('/og-image/{type}/{identifier}.svg', SocialPreviewController::class)->name('og.image');

Route::get('/img', [ImageProxyController::class, 'proxy'])->name('image.proxy');

Route::get('/', function () {
    $locale = app()->getLocale() ?? config('app.locale', 'en');

    return redirect()->route('home', ['locale' => $locale]);
});

$locales = ['cs', 'en', 'de'];
Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => implode('|', $locales)],
    'middleware' => [\App\Http\Middleware\SetLocale::class, \App\Http\Middleware\RequireWebsitePassword::class],
], function () {

    Route::get('/', HomeController::class)->name('home');

    Route::get('/apartments/{apartment:slug}/unavailable', ApartmentUnavailableController::class)
        ->name('apartments.unavailable')
        ->middleware(\App\Http\Middleware\EnsureApartmentState::class.':inactive');

    Route::get('/apartments/{apartment:slug}', ApartmentDetailController::class)
        ->name('apartments.show')
        ->middleware(\App\Http\Middleware\EnsureApartmentState::class.':active');

    Route::get('/contact', ContactController::class)->name('contact');

    Route::get('/about', function () {
        return view('about');
    })->name('about');

    Route::get('/packages', PackagesController::class)->name('packages');
    Route::get('/activities', ActivitiesController::class)->name('activities');
    Route::get('/pricing', PricingController::class)->name('pricing');

    Route::get('/reservation', ReservationController::class)->name('reservation');
    Route::get('/reservation/result', ReservationResultController::class)->name('reservation.result');

    Route::get('/terms-and-conditions', TermsController::class)->name('terms');
    Route::get('/cookies', CookiesController::class)->name('cookies');

});

Route::get('/locale/{locale}', LocaleController::class)->name('locale.switch');
