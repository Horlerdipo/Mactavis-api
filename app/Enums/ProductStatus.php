<?php

namespace App\Enums;

enum ProductStatus: string
{
    case AVAILABLE = "available";
    case UNAVAILABLE = "unavailable";

    public function types(): string
    {
        return match ($this) {
            self::AVAILABLE => 'available',
            self::UNAVAILABLE => 'unavailable',
            default => null,
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::AVAILABLE => 'warning',
            self::UNAVAILABLE => 'success',
            default => 'primary',
        };
    }

    public static function getAllValues(): array
    {
        return array_column(DeliveryStatus::cases(), 'value',1);
    }

    public static function getOptionsForFilament(): array
    {
        $array = [];
        foreach (DeliveryStatus::cases() as $case){
            $array[$case->value] = ucfirst($case->value);
        }
        return $array;
    }
}
