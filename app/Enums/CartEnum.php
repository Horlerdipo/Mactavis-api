<?php

namespace App\Enums;

enum CartEnum: string
{
    case ORIGINAL_BOX_COST = 'original_box_cost';

    public function types(): string
    {
        return match ($this) {
            self::ORIGINAL_BOX_COST => 'original_box_cost',
            default => null,
        };
    }
}
