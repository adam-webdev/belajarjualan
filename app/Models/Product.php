<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'base_price',
        'has_variant',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'has_variant' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class);
    }

    public function combinations()
    {
        return $this->hasMany(ProductCombination::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function couponProducts()
    {
        return $this->hasMany(CouponProduct::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_products');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Attributes
    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first()
            ?? $this->images()->first();
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }

    public function getMinPriceAttribute()
    {
        if (!$this->has_variant) {
            return $this->base_price;
        }

        return $this->combinations()->min('price') ?? $this->base_price;
    }

    public function getMaxPriceAttribute()
    {
        if (!$this->has_variant) {
            return $this->base_price;
        }

        return $this->combinations()->max('price') ?? $this->base_price;
    }
}