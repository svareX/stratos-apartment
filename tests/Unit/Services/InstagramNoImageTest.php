<?php

namespace Tests\Unit\Services;

use App\Models\InstagramPost;
use App\Services\InstagramSyncService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InstagramNoImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_sync_creates_post_when_no_image_url()
    {
        Storage::fake('public');

        $payload = [
            'data' => [
                'user' => [
                    'edge_owner_to_timeline_media' => [
                        'edges' => [
                            ['node' => [
                                'id' => 'inst_noimg',
                                'shortcode' => 'SHORT',
                                'edge_media_to_caption' => ['edges' => [['node' => ['text' => 'No image']]]],
                                'taken_at_timestamp' => now()->timestamp,
                            ]],
                        ],
                    ],
                ],
            ],
        ];

        Http::fake([
            'https://instagram-looter2.p.rapidapi.com/*' => Http::response($payload, 200),
        ]);

        $svc = new InstagramSyncService();
        $svc->sync('user-y', 1);

        $this->assertDatabaseHas('instagram_posts', ['instagram_id' => 'inst_noimg', 'caption' => 'No image']);
        // no file created
        Storage::disk('public')->assertMissing('instagram/inst_noimg.jpg');
    }
}
