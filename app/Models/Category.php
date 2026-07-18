<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'short_description',
        'seo_title',
        'seo_description',
        'status',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Category $category) {
            if (!$category->isForceDeleting()) {
                $category->update([
                    'slug' => $category->slug.'-deleted-'.time(),
                    'name' => $category->name.' (deleted-'.time().')',
                ]);
            }
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
