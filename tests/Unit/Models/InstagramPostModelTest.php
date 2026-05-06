<?php

namespace Tests\Unit\Models;

use App\Models\InstagramPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstagramPostModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_instagram_post_casts_posted_at()
    {
        $post = InstagramPost::create([
            'instagram_id' => 'i1',
            'image_url' => 'photos/i1.jpg',
            'url' => 'https://instagram.com/p/abc',
            'caption' => 'Hello',
            'posted_at' => now(),
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $post->posted_at);
    }
}
