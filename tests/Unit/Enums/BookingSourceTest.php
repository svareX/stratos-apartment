<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\BookingSource;
use Tests\TestCase;

class BookingSourceTest extends TestCase
{
    public function test_options_and_labels(): void
    {
        $options = BookingSource::options();

        $this->assertIsArray($options);
        $this->assertArrayHasKey(BookingSource::Local->value, $options);
        $this->assertArrayHasKey(BookingSource::External->value, $options);

        $this->assertStringContainsString('Local', BookingSource::Local->label());
    }
}
