<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'sku',
        'name',
        'category',
        'brand',
        'tags',
        'summary',
        'description',
        'price',
        'compare_price',
        'cost',
        'stock_qty',
        'cover_image',
        'images',
        'status',
        'is_featured',
        'published_at',
        'notes',
    ];

    protected $casts = [
        'tags' => 'array',
        'images' => 'array',

        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost' => 'decimal:2',

        'stock_qty' => 'integer',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * ✅ กัน null ใน UI แบบถูกวิธี (ไม่ใช้ $attributes เป็น string)
     */
    public function getTagsAttribute($value): array
    {
        if (is_array($value)) return $value;

        // กรณีเจอ JSON string/CSV เก่า
        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) return $decoded;

            $parts = array_map('trim', explode(',', $value));
            return array_values(array_filter($parts, fn($v) => $v !== ''));
        }

        return [];
    }

    public function getImagesAttribute($value): array
    {
        if (is_array($value)) return $value;

        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) return $decoded;

            $parts = array_map('trim', explode(',', $value));
            return array_values(array_filter($parts, fn($v) => $v !== ''));
        }

        return [];
    }

    // ราคาใช้งานจริง
    public function getFinalPriceAttribute(): float
    {
        return round((float) ($this->price ?? 0), 2);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock_qty', '>', 0);
    }

    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        $kw = trim($keyword);
        if ($kw === '') return $query;

        return $query->where(function (Builder $q) use ($kw) {
            $q->where('name', 'like', "%{$kw}%")
              ->orWhere('sku', 'like', "%{$kw}%")
              ->orWhere('category', 'like', "%{$kw}%")
              ->orWhere('brand', 'like', "%{$kw}%");
        });
    }
}