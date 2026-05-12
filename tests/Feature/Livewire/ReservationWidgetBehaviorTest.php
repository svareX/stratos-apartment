<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\ReservationWidget;
use App\Models\Apartment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReservationWidgetBehaviorTest extends TestCase
{
    use RefreshDatabase;

    public function test_month_navigation_changes_display_month_and_year(): void
    {
        Apartment::create([
            'name_en' => 'Widget Nav Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        $now = Carbon::now();
        $prev = Carbon::create($now->year, $now->month, 1)->subMonth();
        $next = Carbon::create($now->year, $now->month, 1)->addMonth();

        Livewire::test(ReservationWidget::class)
            ->call('prevMonth')
            ->assertSet('displayMonth', $prev->month)
            ->call('nextMonth')
            ->call('nextMonth')
            ->assertSet('displayMonth', $next->month);
    }

    public function test_select_date_earlier_resets_start_and_unsets_end(): void
    {
        Apartment::create([
            'name_en' => 'Widget Date Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        Livewire::test(ReservationWidget::class)
            ->set('dateStart', '2026-06-10')
            ->set('dateEnd', '2026-06-12')
            ->call('selectDate', '2026-06-09')
            ->assertSet('dateStart', '2026-06-09')
            ->assertSet('dateEnd', null);
    }

    public function test_select_date_after_start_sets_end_and_nights(): void
    {
        Apartment::create([
            'name_en' => 'Widget Date Apt 2',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 1000,
            'active' => true,
        ]);

        $test = Livewire::test(ReservationWidget::class)
            ->set('dateStart', '2026-09-10')
            ->set('dateEnd', null)
            ->call('selectDate', '2026-09-12');

        $instance = $test->instance();

        $this->assertEquals('2026-09-10', $instance->dateStart);
        $this->assertEquals('2026-09-12', $instance->dateEnd);
        $this->assertEquals(2, $instance->getNightsProperty());
    }
}
