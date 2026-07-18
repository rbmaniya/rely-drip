<?php

namespace App\Models;

use App\Enums\ProductStatus;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'sku_prefix',
        'short_description',
        'description',
        'thumbnail',
        'video_url',
        'weight',
        'weight_unit',
        'status',
        'is_featured',
        'is_best_seller',
        'is_new_arrival',
        'seo_title',
        'seo_description',
        'meta_keywords',
    ];

    protected function casts(): array
    {
        return [
            'status' => ProductStatus::class,
            'is_featured' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_new_arrival' => 'boolean',
            'weight' => 'decimal:3',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (Product $product) {
            if (! $product->isForceDeleting()) {
                $product->update([
                    'slug' => $product->slug.'-deleted-'.time(),
                    'title' => $product->title.' (deleted-'.time().')',
                ]);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class)->orderBy('sort_order');
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getAverageRatingAttribute(): float
    {
        return round((float) $this->reviews()->approved()->avg('rating'), 1);
    }

    public function getReviewCountAttribute(): int
    {
        return $this->reviews()->approved()->count();
    }

    public function getTotalStockAttribute(): int
    {
        return (int) $this->variations->sum('stock');
    }

    public function getMinPriceAttribute(): ?float
    {
        return $this->variations->min('price');
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return $this->variations->isNotEmpty() && $this->total_stock === 0;
    }

    public function getVideoEmbedUrlAttribute(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([\w-]{11})/', $this->video_url, $matches)) {
            return "https://www.youtube.com/embed/{$matches[1]}";
        }

        if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $this->video_url, $matches)) {
            return "https://player.vimeo.com/video/{$matches[1]}";
        }

        return null;
    }

    public function getIsDirectVideoFileAttribute(): bool
    {
        return (bool) $this->video_url
            && ! $this->video_embed_url
            && (bool) preg_match('/\.(mp4|webm|ogg)(\?.*)?$/i', $this->video_url);
    }

    public function scopeActive($query)
    {
        return $query->where('status', ProductStatus::Active);
    }
}
