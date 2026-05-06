<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\ApartmentType;
use Tests\TestCase;

class ApartmentTypeTest extends TestCase
{
    public function test_options_and_labels(): void
    {
        $options = ApartmentType::options();

        $this->assertIsArray($options);
        $this->assertArrayHasKey(ApartmentType::Mountains->value, $options);
        $this->assertArrayHasKey(ApartmentType::Vineyard->value, $options);

        $this->assertStringContainsString('Mountains', ApartmentType::Mountains->label());
    }
}
