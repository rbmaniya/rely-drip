<?php

namespace App\Enums;

enum GoldPurity: string
{
    case K9 = '9k';
    case K12 = '12k';
    case K16 = '16k';
    case K18 = '18k';
    case K22 = '22k';
    case K24 = '24k';

    public function label(): string
    {
        return strtoupper($this->value);
    }
}
