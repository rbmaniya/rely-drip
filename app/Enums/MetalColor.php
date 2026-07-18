<?php

namespace App\Enums;

enum MetalColor: string
{
    case Gold = 'gold';
    case Silver = 'silver';
    case RoseGold = 'rose_gold';

    public function label(): string
    {
        return match ($this) {
            self::Gold => 'Gold',
            self::Silver => 'Silver',
            self::RoseGold => 'Rose Gold',
        };
    }
}
