<?php

declare(strict_types=1);

namespace Tests\Feature\Integration;

use App\Mail\ReservationConfirmation;
use App\Models\Apartment;
use App\Models\ApartmentPackage;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class TestReservationForm extends \App\Livewire\ReservationForm
{
    protected function rules()
    {
        $r = parent::rules();
        $r['email'] = 'required|email';

        return $r;
    }
}

class ReservationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirm_creates_reservation_and_queues_mail(): void
    {
        Mail::fake();

        // Avoid DNS-based email validation in tests by binding a test subclass
        $this->app->bind(\App\Livewire\ReservationForm::class, function ($app) {
            return new class extends \App\Livewire\ReservationForm
            {
                protected function rules()
                {
                    $r = parent::rules();
                    $r['email'] = 'required|email';

                    return $r;
                }
            };
        });

        $apt = Apartment::create([
            'name_en' => 'Flow Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 500,
            'active' => true,
        ]);

        $pkg = ApartmentPackage::create([
            'apartment_id' => $apt->id,
            'name' => 'Breakfast',
            'price' => 100,
        ]);

        $start = now()->addDays(5)->toDateString();
        $end = now()->addDays(7)->toDateString();

        $test = Livewire::test(TestReservationForm::class)
            ->set('apartment_id', $apt->id)
            ->call('updateApartmentDetails')
            ->set('apartment_package_id', $pkg->id)
            ->set('start_date', $start)
            ->set('end_date', $end)
            ->call('nextStep')
            ->assertSet('step', 2)
            ->set('first_name', 'John')
            ->set('last_name', 'Doe')
            ->set('email', 'john@example.com')
            ->set('phone', '+420123456789')
            ->set('address', 'Some street 1')
            ->set('city', 'Prague')
            ->set('postal_code', '10000')
            ->set('country', 'Czechia')
            ->call('confirm')
            ->assertHasNoErrors();

        // Ensure reservation exists in DB
        $this->assertDatabaseCount('reservations', 1);
        $this->assertDatabaseHas('reservations', [
            'apartment_id' => $apt->id,
        ]);

        // Mail queued
        Mail::assertQueued(ReservationConfirmation::class);

        // Session flag set
        $this->assertTrue(session('reservation_completed') === true || session()->has('reservation_completed'));
    }

    public function test_next_step_requires_apartment_package_and_dates(): void
    {
        $apt = Apartment::create([
            'name_en' => 'Flow Apt 2',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 500,
            'active' => true,
        ]);

        $test = Livewire::test(TestReservationForm::class)
            ->set('apartment_id', $apt->id)
            ->call('updateApartmentDetails')
            ->set('apartment_package_id', '')
            ->call('nextStep')
            ->assertSet('step', 1); // should not advance due to missing package

        // Now set package but not dates
        $pkg = ApartmentPackage::create([
            'apartment_id' => $apt->id,
            'name' => 'Breakfast',
            'price' => 50,
        ]);

        $test = Livewire::test(TestReservationForm::class)
            ->set('apartment_id', $apt->id)
            ->call('updateApartmentDetails')
            ->set('apartment_package_id', $pkg->id)
            ->call('nextStep')
            ->assertSet('step', 1); // should not advance due to missing dates
    }
}
