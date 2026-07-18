<?php

namespace App\Enums;

enum Metal: string
{
    case Gold = 'gold';
    case Silver = 'silver';
    case Platinum = 'platinum';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
