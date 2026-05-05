<?php

namespace App\Enums;

enum ReviewSource: string
{
    case Local = 'local';
    case External = 'external';

    public function label(): string
    {
        return match ($this) {
            self::Local => __('Local'),
            self::External => __('External'),
        };
    }

    public static function options(): array
    {
        return [
            self::Local->value => self::Local->label(),
            self::External->value => self::External->label(),
        ];
    }
}
