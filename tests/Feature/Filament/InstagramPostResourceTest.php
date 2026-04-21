<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Resources\InstagramPosts\Pages\CreateInstagramPost;
use App\Filament\Resources\InstagramPosts\Pages\EditInstagramPost;
use App\Models\InstagramPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InstagramPostResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_filament_can_create_instagram_post(): void
    {
        $data = [
            'instagram_id' => 'insta-123',
            'image_url' => 'https://example.com/image.jpg',
            'url' => 'https://instagram.com/p/insta-123',
            'posted_at' => now()->toDateTimeString(),
            'caption' => 'Nice caption',
        ];

        Livewire::test(CreateInstagramPost::class)
            ->set('data', $data)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('instagram_posts', ['instagram_id' => 'insta-123', 'caption' => 'Nice caption']);
    }

    public function test_filament_can_update_instagram_post(): void
    {
        $post = InstagramPost::create([
            'instagram_id' => 'insta-456',
            'image_url' => 'https://example.com/old.jpg',
            'url' => 'https://instagram.com/p/insta-456',
            'posted_at' => now(),
            'caption' => 'Old caption',
        ]);

        $update = [
            'caption' => 'Updated caption',
            'image_url' => 'https://example.com/new.jpg',
        ];

        Livewire::test(EditInstagramPost::class, ['record' => $post->id])
            ->set('data', array_merge($post->toArray(), $update))
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('instagram_posts', ['id' => $post->id, 'caption' => 'Updated caption']);

        // Delete via model to confirm removal behavior
        $post->delete();
        $this->assertDatabaseMissing('instagram_posts', ['id' => $post->id]);
    }
}
