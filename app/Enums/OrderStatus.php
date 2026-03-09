<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    public static function values(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }
}
