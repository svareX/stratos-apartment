<?php

namespace App\Enums;

enum ApartmentType: string
{
    case Mountains = 'mountains';
    case Vineyard = 'vineyards';

    public function label(): string
    {
        return match ($this) {
            self::Mountains => __('Mountains'),
            self::Vineyard => __('Vineyard'),
        };
    }

    public static function options(): array
    {
        return [
            self::Mountains->value => self::Mountains->label(),
            self::Vineyard->value => self::Vineyard->label(),
        ];
    }
}
