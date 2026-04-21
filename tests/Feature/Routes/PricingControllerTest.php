<?php

declare(strict_types=1);

namespace Tests\Feature\Routes;

use App\Models\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PricingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pricing_orders_by_name_when_column_exists(): void
    {
        Schema::shouldReceive('hasColumn')->andReturn(true);

        Apartment::create([
            'name_en' => 'B Place',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
        ]);

        Apartment::create([
            'name_en' => 'A Place',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 120,
            'active' => true,
        ]);

        $res = $this->withSession(['website_authenticated' => true])->get(route('pricing', ['locale' => 'en']));

        $res->assertStatus(200);
        $res->assertViewIs('pricing');
        $res->assertViewHas('apartments', function ($apts) {
            return $apts->pluck('name_en')->first() === 'A Place';
        });
    }

    public function test_pricing_falls_back_to_id_when_column_missing(): void
    {
        Schema::shouldReceive('hasColumn')->with('apartments', 'name_en')->andReturn(false);

        $first = Apartment::create([
            'name_en' => 'First',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'active' => true,
        ]);

        $second = Apartment::create([
            'name_en' => 'Second',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 120,
            'active' => true,
        ]);

        $res = $this->withSession(['website_authenticated' => true])->get(route('pricing', ['locale' => 'en']));

        $res->assertStatus(200);
        $res->assertViewIs('pricing');
        $res->assertViewHas('apartments', function ($apts) use ($first) {
            return $apts->first()->id === $first->id;
        });
    }
}
