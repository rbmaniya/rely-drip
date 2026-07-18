<?php

namespace App\Models;

use App\Enums\GoldPurity;
use App\Enums\Metal;
use App\Enums\MetalColor;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariation extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'product_id',
        'metal',
        'color',
        'gold_purity',
        'sku',
        'price',
        'stock',
        'min_stock_alert',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'metal' => Metal::class,
            'color' => MetalColor::class,
            'gold_purity' => GoldPurity::class,
            'price' => 'decimal:2',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock <= $this->min_stock_alert;
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return $this->stock <= 0;
    }

    public function getLabelAttribute(): string
    {
        $parts = [$this->metal->label(), $this->color->label()];

        if ($this->gold_purity) {
            $parts[] = $this->gold_purity->label();
        }

        return implode(' / ', $parts);
    }
}
