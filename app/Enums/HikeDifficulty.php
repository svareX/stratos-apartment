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

    public function icon(): string
    {
        return match ($this) {
            self::Easy => '🟢',
            self::Medium => '🟡',
            self::Hard => '🔴',
            self::Extreme => '⚫',
        };
    }

    public function textColor(): string
    {
        return match ($this) {
            self::Easy => 'text-teal',
            self::Medium => 'text-[#ffd166]',
            self::Hard => 'text-[#ef9a9a]',
            self::Extreme => 'text-gray-300',
        };
    }

    public function bgColor(): string
    {
        return match ($this) {
            self::Easy => 'bg-[rgba(0,201,167,.15)]',
            self::Medium => 'bg-[rgba(255,200,50,.12)]',
            self::Hard => 'bg-[rgba(229,57,53,.12)]',
            self::Extreme => 'bg-[rgba(255,255,255,.1)]',
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