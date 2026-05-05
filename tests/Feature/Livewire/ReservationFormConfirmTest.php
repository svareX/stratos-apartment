<?php

namespace Tests\Feature\Livewire;

use App\Livewire\ReservationForm;
use App\Models\Apartment;
use App\Models\ApartmentPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class ReservationFormConfirmTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_fails_for_invalid_email()
    {
        $apt = Apartment::create([
            'name_en' => 'Confirm Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 150,
            'active' => true,
        ]);

        $pkg = ApartmentPackage::create([
            'apartment_id' => $apt->id,
            'name_en' => 'Std',
            'price' => 50,
        ]);

        $start = now()->addDays(5)->toDateString();
        $end = now()->addDays(7)->toDateString();

        Livewire::test(ReservationForm::class)
            ->set('apartment_id', $apt->id)
            ->call('updateApartmentDetails')
            ->set('apartment_package_id', $pkg->id)
            ->set('start_date', $start)
            ->set('end_date', $end)
            ->set('first_name', 'John')
            ->set('last_name', 'Doe')
            ->set('email', 'not-an-email')
            ->set('phone', '+420123456789')
            ->set('address', 'Street 1')
            ->set('city', 'City')
            ->set('postal_code', '12345')
            ->set('country', 'Country')
                ->call('nextStep')
                ->call('nextStep')
                ->assertHasErrors(['email']);
    }
}

