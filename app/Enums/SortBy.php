<?php

namespace App\Enums;

enum SortBy: string
{
    case ALPHABETICAL_ASC = "a-z";
    case ALPHABETICAL_DESC = "z-a";
    case PRICE_ASC = 'low_price';
    case PRICE_DESC = 'high_price';

    public function types(): string
    {
        return match ($this) {
            self::ALPHABETICAL_ASC => 'a-z',
            self::ALPHABETICAL_DESC => 'z-a',
            self::PRICE_ASC => 'low_price',
            self::PRICE_DESC => 'high_price',
            default => null,
        };
    }

    public function names(): string
    {
        return match ($this) {
            self::ALPHABETICAL_ASC => 'Name, A to Z',
            self::ALPHABETICAL_DESC => 'Name, Z-A',
            self::PRICE_ASC => 'Price, Low to High',
            self::PRICE_DESC => 'Price, High to Low',
            default => null,
        };
    }

    public static function getAllValues(): array
    {
        return array_column(SortBy::cases(), 'value');
    }

    public static function getOptionsForFilament(): array
    {
        $array = [];
        foreach (SortBy::cases() as $case){
            $array[$case->value] = ucfirst($case->value);
        }
        return $array;
    }
}
