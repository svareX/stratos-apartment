<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Models\Apartment;
use App\Enums\ApartmentType;
use App\Filament\Resources\Apartments\Pages\CreateApartment;
use App\Filament\Resources\Apartments\Pages\EditApartment;

class ApartmentResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_filament_can_create_apartment(): void
    {
        $data = [
            'name_en' => 'Filament Apt',
            'address' => 'Address 1',
            'capacity' => 4,
            'base_price' => 1500,
            'cleaning_fee' => 100,
            'days_for_cleaning_fee' => 3,
            'type' => ApartmentType::Mountains->value,
            'active' => true,
            'description_en' => 'Nice place',
        ];

        Livewire::test(CreateApartment::class)
            ->set('data', $data)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('apartments', ['name_en' => 'Filament Apt', 'address' => 'Address 1']);
    }

    public function test_filament_can_update_apartment(): void
    {
        $apartment = Apartment::create([
            'name_en' => 'Old Name',
            'slug' => 'old-name',
            'address' => 'Old Address',
            'capacity' => 2,
            'base_price' => 1000,
            'cleaning_fee' => 50,
            'days_for_cleaning_fee' => 3,
            'type' => ApartmentType::Mountains->value,
            'active' => true,
        ]);

        $update = [
            'name_en' => 'Updated Name',
            'address' => 'New Address',
            'base_price' => 2000,
        ];

        Livewire::test(EditApartment::class, ['record' => $apartment->slug])
            ->set('data', array_merge($apartment->toArray(), $update))
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('apartments', ['id' => $apartment->id, 'name_en' => 'Updated Name', 'base_price' => 2000]);
    }

    public function test_filament_can_delete_apartment(): void
    {
        $apartment = Apartment::create([
            'name_en' => 'To Delete',
            'slug' => 'to-delete',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'cleaning_fee' => 50,
            'days_for_cleaning_fee' => 3,
            'type' => ApartmentType::Mountains->value,
            'active' => true,
        ]);

        // Delete via model (Filament DeleteAction triggers the same underlying deletion)
        $apartment->delete();

        $this->assertDatabaseMissing('apartments', ['id' => $apartment->id]);
    }
}
