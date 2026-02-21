<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    /**
     * Mass-assignable attributes
     */
    protected $fillable = [
        'sku',
        'barcode',
        'name',
        'slug',
        'short_description',
        'description',
        'category_name',
        'brand_name',
        'tags',
        'price',
        'compare_at_price',
        'cost',
        'tax_rate',
        'discount_type',
        'discount_value',
        'stock_qty',
        'reserved_qty',
        'reorder_point',
        'stock_status',
        'weight_kg',
        'length_cm',
        'width_cm',
        'height_cm',
        'shipping_class',
        'cover_image_url',
        'gallery_images',
        'status',
        'is_featured',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'notes',
        'attributes',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'tags' => 'array',
        'gallery_images' => 'array',
        'attributes' => 'array',

        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'cost' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'discount_value' => 'decimal:2',

        'weight_kg' => 'decimal:3',
        'length_cm' => 'decimal:2',
        'width_cm' => 'decimal:2',
        'height_cm' => 'decimal:2',

        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Defaults for JSON fields to avoid null issues in UI
     */
    protected $attributes = [
        'tags' => '[]',
        'gallery_images' => '[]',
        'attributes' => '{}',
    ];

    /* -----------------------------
     | Accessors (computed values)
     |-----------------------------*/

    // จำนวนพร้อมขายจริง = stock_qty - reserved_qty
    public function getAvailableQtyAttribute(): int
    {
        $stock = (int) ($this->stock_qty ?? 0);
        $reserved = (int) ($this->reserved_qty ?? 0);
        $available = $stock - $reserved;
        return $available < 0 ? 0 : $available;
    }

    // ราคา “หลังส่วนลด” (ยังไม่รวมภาษี)
    public function getFinalPriceAttribute(): float
    {
        $price = (float) ($this->price ?? 0);

        $type = $this->discount_type ?? 'none';
        $value = (float) ($this->discount_value ?? 0);

        if ($type === 'percent' && $value > 0) {
            $discount = $price * ($value / 100);
            return max(0, round($price - $discount, 2));
        }

        if ($type === 'fixed' && $value > 0) {
            return max(0, round($price - $value, 2));
        }

        return round($price, 2);
    }

    // ราคา “รวมภาษี” (ถ้ามี tax_rate)
    public function getFinalPriceWithTaxAttribute(): float
    {
        $final = $this->final_price;
        $taxRate = (float) ($this->tax_rate ?? 0);

        if ($taxRate <= 0) return round($final, 2);

        $withTax = $final + ($final * ($taxRate / 100));
        return round($withTax, 2);
    }

    /* -----------------------------
     | Query Scopes
     |-----------------------------*/

    // เฉพาะสินค้าที่ขายได้
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    // เฉพาะสินค้าที่เด่น (featured)
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    // เฉพาะที่มีของพร้อมขายจริง
    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock_status', 'in_stock')
            ->whereRaw('(stock_qty - reserved_qty) > 0');
    }

    // ค้นหาด้วย keyword แบบง่าย
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        $kw = trim($keyword);
        if ($kw === '') return $query;

        return $query->where(function (Builder $q) use ($kw) {
            $q->where('name', 'like', "%{$kw}%")
              ->orWhere('sku', 'like', "%{$kw}%")
              ->orWhere('barcode', 'like', "%{$kw}%")
              ->orWhere('category_name', 'like', "%{$kw}%")
              ->orWhere('brand_name', 'like', "%{$kw}%");
        });
    }

    public function getStockStatusTextAttribute()
{
    return match ($this->stock_status) {
        'in_stock' => 'มีสินค้า',
        'out_of_stock' => 'สินค้าหมด',
        'preorder' => 'พรีออเดอร์',
        default => '-',
    };
}
}