<?php

namespace Tests\Unit\Jobs;

use App\Jobs\GenerateSitemap;
use App\Jobs\SyncBookingJob;
use App\Jobs\SyncKnowledgeBaseJob;
use App\Jobs\SyncReviewsJob;
use App\Services\Ical\IcalImportService;
use App\Services\BookingReviewsService;
use App\Services\SitemapGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Mockery;
use Tests\TestCase;

class JobsTest extends TestCase
{
    use RefreshDatabase;

    public function test_sync_booking_job_calls_service()
    {
        $mock = Mockery::mock(IcalImportService::class);
        $mock->shouldReceive('syncAll')->once();

        $job = new SyncBookingJob();
        $job->handle($mock);
    }

    public function test_sync_reviews_job_iterates_hotel_ids_and_locales()
    {
        config(['services.booking.hotel_ids' => [11, 22]]);

        $mock = Mockery::mock(BookingReviewsService::class);
        // 2 hotels * 4 locales => 8 calls
        $mock->shouldReceive('import')->times(8);

        $job = new SyncReviewsJob();
        $job->handle($mock);
    }

    public function test_generate_sitemap_job_calls_generator()
    {
        $mock = Mockery::mock(SitemapGenerator::class);
        $mock->shouldReceive('generate')->once();

        $this->app->instance(SitemapGenerator::class, $mock);

        $job = new GenerateSitemap();
        $job->handle();
    }

    public function test_sync_knowledge_base_calls_artisan()
    {
        Artisan::shouldReceive('call')->once()->with('knowledge:sync');

        $job = new SyncKnowledgeBaseJob();
        $job->handle();
    }
}
