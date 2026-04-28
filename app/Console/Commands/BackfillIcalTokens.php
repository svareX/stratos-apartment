<?php

namespace App\Console\Commands;

use App\Models\Apartment;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class BackfillIcalTokens extends Command
{
    protected $signature = 'apartments:backfill-ical-tokens {--chunk=100}';

    protected $description = 'Backfill missing ical_export_token for apartments';

    public function handle(): int
    {
        $chunk = (int) $this->option('chunk');

        Apartment::whereNull('ical_export_token')
            ->chunk($chunk, function ($apartments) {
                foreach ($apartments as $apartment) {
                    $tries = 0;

                    do {
                        $token = Str::random(48);
                        $exists = Apartment::where('ical_export_token', $token)->exists();
                        $tries++;
                    } while ($exists && $tries < 5);

                    $apartment->forceFill(['ical_export_token' => $token])->save();

                    $this->info("Backfilled apartment {$apartment->id} with token");
                }
            });

        $this->info('Done.');

        return 0;
    }
}
