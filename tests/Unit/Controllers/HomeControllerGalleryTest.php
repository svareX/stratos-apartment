<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\HomeController;
use App\Models\Apartment;
use App\Models\Photo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class HomeControllerGalleryTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_images_include_photo_tags_when_present()
    {
        // Create apartment that will be included on the homepage
        $apt = Apartment::create([
            'name' => 'Test Apt',
            'address' => '123 Test Street',
            'capacity' => 2,
            'base_price' => 100.00,
            'active' => true,
            'slug' => 'test-apt',
        ]);

        // Photo with tag (other photo)
        $photoWithTag = Photo::create([
            'apartment_id' => $apt->id,
            'path' => 'photos/1.jpg',
            'is_main' => false,
            'position' => 1,
            'tag_en' => 'Gallery Tag',
        ]);

        // Photo without tag
        Photo::create([
            'apartment_id' => $apt->id,
            'path' => 'photos/2.jpg',
            'is_main' => false,
            'position' => 2,
        ]);

        $controller = new HomeController;
        $view = $controller->__invoke(new Request);

        $data = $view->getData();

        $this->assertArrayHasKey('galleryImages', $data);

        $gallery = $data['galleryImages'];
        $this->assertNotEmpty($gallery);

        // Find the photo we created with a tag and assert accessor returns it
        $found = null;
        foreach ($gallery as $g) {
            if ($g->id === $photoWithTag->id) {
                $found = $g;
                break;
            }
        }

        $this->assertNotNull($found, 'Tagged photo not present in galleryImages');
        $this->assertNotEmpty($found->tag, 'Photo tag should not be empty when tag fields are present');
        $this->assertEquals('Gallery Tag', $found->tag);
    }
}
