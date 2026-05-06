<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\ReviewSource;
use Tests\TestCase;

class ReviewSourceTest extends TestCase
{
    public function test_options_and_labels(): void
    {
        $options = ReviewSource::options();

        $this->assertIsArray($options);
        $this->assertArrayHasKey(ReviewSource::Local->value, $options);
        $this->assertArrayHasKey(ReviewSource::External->value, $options);

        $this->assertStringContainsString('Local', ReviewSource::Local->label());
    }
}
