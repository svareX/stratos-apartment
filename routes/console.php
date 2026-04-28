<?php

use App\Jobs\GenerateSitemap;
use App\Services\InstagramSyncService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('reservations:sync-ical', function (\App\Services\Ical\IcalImportService $importService) {
    $result = $importService->syncAll();

    $this->info('iCal sync completed.');
    $this->line("Apartments: {$result['apartments']}");
    $this->line("Created: {$result['created']}");
    $this->line("Updated: {$result['updated']}");
    $this->line("Cancelled: {$result['cancelled']}");
    $this->line("Errors: {$result['errors']}");
})->purpose('Sync reservations from external iCal feeds');

Schedule::command('reservations:sync-ical')->everyFifteenMinutes();

Schedule::call(function () {
    app(InstagramSyncService::class)->sync('apartmanstratos');
})->daily();

Schedule::job(new GenerateSitemap)->dailyAt('00:00')->withoutOverlapping()->onOneServer();
