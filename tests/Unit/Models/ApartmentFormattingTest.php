<?php

namespace Tests\Unit\Models;

use App\Models\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApartmentFormattingTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkin_checkout_formatting_and_invalid_values()
    {
        $apt = Apartment::create([
            'name_en' => 'Fmt Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'check_in_time' => '15:00:00',
            'check_out_time' => '10:00:00',
        ]);

        $this->assertEquals('15:00', $apt->check_in_time_formatted);
        $this->assertEquals('10:00', $apt->check_out_time_formatted);

        // invalid time returns original value
        $apt->check_in_time = 'not-a-time';
        $this->assertEquals('not-a-time', $apt->getCheckInTimeFormattedAttribute());
    }

    public function test_tags_locale_behavior()
    {
        $apt = Apartment::create([
            'name_en' => 'Tags Apt',
            'address' => 'Addr',
            'capacity' => 2,
            'base_price' => 100,
            'tags_en' => ['one', 'two'],
            'tags_cs' => ['jedna'],
        ]);

        app()->setLocale('cs');
        $this->assertEquals(['jedna'], $apt->tags);

        app()->setLocale('en');
        $this->assertEquals(['one', 'two'], $apt->tags);
    }
}
