<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Models\Apartment;
use App\Models\ApartmentPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReservationFormPrefillTest extends TestCase
{
    use RefreshDatabase;

    public function test_mount_prefills_apartment_and_package_from_url_params(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Prefill Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 500,
            'active' => true,
            'slug' => 'prefill-apt',
        ]);

        $pkg = ApartmentPackage::create([
            'apartment_id' => $apt->id,
            'name_en' => 'Breakfast',
            'price' => 150.0,
            'icon' => '☕',
        ]);

        Livewire::test(\App\Livewire\ReservationForm::class, ['apartment' => $apt->slug, 'apartment_id' => $apt->id, 'package' => $pkg->id])
            ->assertSet('apartment_id', $apt->id)
            ->assertSet('apartment_package_id', (string) $pkg->id)
            ->assertSet('packagePrice', 150.0)
            ->assertSet('selectedPackageName', 'Breakfast');
    }
}
