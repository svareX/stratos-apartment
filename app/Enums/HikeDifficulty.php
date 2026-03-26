<?php

namespace App\Enums;

enum HikeDifficulty: string
{
    case Easy = 'easy';
    case Medium = 'medium';
    case Hard = 'hard';
    case Extreme = 'extreme';

    public function label(): string
    {
        return match ($this) {
            self::Easy => __('Easy'),
            self::Medium => __('Medium'),
            self::Hard => __('Hard'),
            self::Extreme => __('Extreme'),
        };
    }

    public static function options(): array
    {
        return [
            self::Easy->value => self::Easy->label(),
            self::Medium->value => self::Medium->label(),
            self::Hard->value => self::Hard->label(),
            self::Extreme->value => self::Extreme->label(),
        ];
    }
}