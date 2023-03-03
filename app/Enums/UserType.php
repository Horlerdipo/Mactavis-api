<?php

namespace App\Enums;

enum UserType: string
{
    case RESELLER = 'reseller';
    case CUSTOMER = 'customer';

    public function types(): string
    {
        return match ($this) {
            self::RESELLER => 'reseller',
            self::CUSTOMER => 'customer',
            default => null,
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::RESELLER => 'secondary',
            self::CUSTOMER => 'primary',
            default => 'warning',
        };
    }

    public static function getAllValues(): array
    {
        return array_column(UserType::cases(), 'value', 1);
    }

    public static function getOptionsForFilament(): array
    {
        $array = [];
        foreach (UserType::cases() as $case) {
            $array[$case->value] = ucfirst($case->value);
        }

        return $array;
    }
}
