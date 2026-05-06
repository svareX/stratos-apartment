<?php

namespace Tests\Unit\Services;

use App\Services\InstagramSyncService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InstagramSyncServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_sync_stores_image_and_creates_instagram_post()
    {
        Storage::fake('public');

        $imageUrl = 'https://cdn.example.com/img.jpg';

        $payload = [
            'data' => [
                'user' => [
                    'edge_owner_to_timeline_media' => [
                        'edges' => [
                            ['node' => [
                                'id' => 'inst1',
                                'thumbnail_src' => $imageUrl,
                                'shortcode' => 'ABC',
                                'edge_media_to_caption' => ['edges' => [['node' => ['text' => 'Nice']]]],
                                'taken_at_timestamp' => now()->timestamp,
                            ]],
                        ],
                    ],
                ],
            ],
        ];

        Http::fake([
            'https://instagram-looter2.p.rapidapi.com/*' => Http::response($payload, 200),
            $imageUrl => Http::response('IMAGE_BYTES', 200),
        ]);

        $svc = new InstagramSyncService;
        $svc->sync('user123', 1);

        $this->assertDatabaseHas('instagram_posts', [
            'instagram_id' => 'inst1',
            'caption' => 'Nice',
        ]);

        Storage::disk('public')->assertExists('instagram/inst1.jpg');
    }
}
