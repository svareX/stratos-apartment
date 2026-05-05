<?php

namespace Tests\Unit\Services;

use App\Models\InstagramPost;
use App\Services\InstagramSyncService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InstagramSyncServiceEdgeCasesTest extends TestCase
{
    use RefreshDatabase;

    public function test_sync_skips_empty_nodes()
    {
        Http::fake([
            'https://instagram-looter2.p.rapidapi.com/*' => Http::response(['data' => ['user' => ['edge_owner_to_timeline_media' => ['edges' => [['node' => []], ['node' => null]]]]]], 200),
        ]);

        $svc = new InstagramSyncService();
        $svc->sync('user-x', 2);

        $this->assertDatabaseCount('instagram_posts', 0);
    }

    public function test_sync_handles_missing_image_download_gracefully()
    {
        Storage::fake('public');

        $imageUrl = 'https://cdn.example.com/missing.jpg';

        $payload = [
            'data' => [
                'user' => [
                    'edge_owner_to_timeline_media' => [
                        'edges' => [
                            ['node' => [
                                'id' => 'inst2',
                                'thumbnail_src' => $imageUrl,
                                'shortcode' => 'XYZ',
                                'edge_media_to_caption' => ['edges' => [['node' => ['text' => 'Caption here']]]],
                                'taken_at_timestamp' => now()->timestamp,
                            ]],
                        ],
                    ],
                ],
            ],
        ];

        Http::fake([
            'https://instagram-looter2.p.rapidapi.com/*' => Http::response($payload, 200),
            $imageUrl => Http::response('', 500),
        ]);

        $svc = new InstagramSyncService();
        $svc->sync('user-x', 1);

        $this->assertDatabaseHas('instagram_posts', ['instagram_id' => 'inst2', 'caption' => 'Caption here']);
        // file should be created even if content empty
        Storage::disk('public')->assertExists('instagram/inst2.jpg');
    }
}
