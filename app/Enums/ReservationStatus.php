<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
    case Completed = 'completed';
    case Refunded = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::Pending => __('Pending'),
            self::Confirmed => __('Confirmed'),
            self::Cancelled => __('Cancelled'),
            self::Completed => __('Completed'),
            self::Refunded => __('Refunded'),
        };
    }

    public static function options(): array
    {
        return [
            self::Pending->value => self::Pending->label(),
            self::Confirmed->value => self::Confirmed->label(),
            self::Cancelled->value => self::Cancelled->label(),
            self::Completed->value => self::Completed->label(),
            self::Refunded->value => self::Refunded->label(),
        ];
    }
}
