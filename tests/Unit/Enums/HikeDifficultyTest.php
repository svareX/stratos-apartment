<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use Tests\TestCase;
use App\Enums\HikeDifficulty;

class HikeDifficultyTest extends TestCase
{
    public function test_options_and_labels(): void
    {
        $options = HikeDifficulty::options();

        $this->assertIsArray($options);
        $this->assertArrayHasKey(HikeDifficulty::Easy->value, $options);
        $this->assertArrayHasKey(HikeDifficulty::Medium->value, $options);
        $this->assertArrayHasKey(HikeDifficulty::Hard->value, $options);
        $this->assertArrayHasKey(HikeDifficulty::Extreme->value, $options);

        $this->assertStringContainsString('Easy', HikeDifficulty::Easy->label());
    }
}
