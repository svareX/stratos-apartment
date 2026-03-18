<?php

namespace App\Enums;

enum ApartmentType
{
    case Mountains = 'mountains';
    case Vineyard = 'vineyards';

    public function label(): string
    {
        return match ($this) {
            self::Mountains => 'Mountains',
            self::Vineyard => 'Vineyard',
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
