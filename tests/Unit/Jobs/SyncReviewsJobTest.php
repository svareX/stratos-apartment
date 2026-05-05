<?php

namespace Tests\Unit\Jobs;

use App\Jobs\SyncReviewsJob;
use App\Services\BookingReviewsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class SyncReviewsJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_calls_import_for_configured_hotels()
    {
        config(['services.booking.hotel_ids' => [101, 202]]);

        $mock = Mockery::mock(BookingReviewsService::class);
        $mock->shouldReceive('import')->times(2 * 4); // two hotels * four locales
        $this->instance(BookingReviewsService::class, $mock);

        $job = new SyncReviewsJob();
        $job->handle($mock);

        $this->assertTrue(true);
    }

    public function test_handle_no_hotels_logs_and_returns()
    {
        config(['services.booking.hotel_ids' => null]);
        putenv('BOOKING_HOTEL_ID=');

        $mock = Mockery::mock(BookingReviewsService::class);
        $this->instance(BookingReviewsService::class, $mock);

        $job = new SyncReviewsJob();
        $job->handle($mock);

        $this->assertTrue(true);
    }
}
