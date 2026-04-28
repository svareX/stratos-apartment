<?php

namespace App\Jobs;

use App\Services\Ical\IcalImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncBookingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(IcalImportService $icalImportService): void
    {
        try {
            $icalImportService->syncAll();
        } catch (\Throwable $e) {
            Log::error('SyncBookingJob failed', ['exception' => $e]);
            throw $e;
        }
    }
}
