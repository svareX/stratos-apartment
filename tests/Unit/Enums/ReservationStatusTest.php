<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\ReservationStatus;
use Tests\TestCase;

class ReservationStatusTest extends TestCase
{
    public function test_options_and_labels(): void
    {
        $options = ReservationStatus::options();

        $this->assertIsArray($options);
        $this->assertArrayHasKey(ReservationStatus::Pending->value, $options);
        $this->assertArrayHasKey(ReservationStatus::Confirmed->value, $options);

        $this->assertStringContainsString('Pending', ReservationStatus::Pending->label());
    }
}
