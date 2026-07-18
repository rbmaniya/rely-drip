<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

class CustomOrderRequest extends Model
{
    use HasUuid;

    protected $fillable = [
        'piece_type',
        'stone_preference',
        'metal_preference',
        'engraving',
        'estimated_price',
        'name',
        'whatsapp',
        'email',
        'country',
        'vision',
        'design_reference',
    ];

    protected function casts(): array
    {
        return [
            'estimated_price' => 'decimal:2',
        ];
    }
}
