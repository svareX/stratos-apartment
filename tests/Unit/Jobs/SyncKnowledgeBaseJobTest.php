<?php

namespace Tests\Unit\Jobs;

use App\Jobs\SyncKnowledgeBaseJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Mockery;
use Tests\TestCase;

class SyncKnowledgeBaseJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_calls_artisan_command()
    {
        Artisan::shouldReceive('call')->once()->with('knowledge:sync');

        $job = new SyncKnowledgeBaseJob();
        $job->handle();

        $this->assertTrue(true);
    }
}
