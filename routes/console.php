<?php

use App\Jobs\GenerateSitemap;
use App\Services\InstagramSyncService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    app(InstagramSyncService::class)->sync('apartmanstratos');
})->daily();

Schedule::job(new GenerateSitemap)->dailyAt('00:00')->withoutOverlapping()->onOneServer();
