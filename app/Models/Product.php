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
        'has_variant' => 'integer',
        'is_active' => 'integer',
    ];

    protected $attributes = [
        'has_variant' => 0,
        'is_active' => 1,
    ];

    // Mutator untuk has_variant
    public function setHasVariantAttribute($value)
    {
        $this->attributes['has_variant'] = $value ? 1 : 0;
    }

    // Accessor untuk has_variant
    public function getHasVariantAttribute($value)
    {
        return (int) $value;
    }

    // Mutator untuk is_active
    public function setIsActiveAttribute($value)
    {
        $this->attributes['is_active'] = $value ? 1 : 0;
    }

    // Accessor untuk is_active
    public function getIsActiveAttribute($value)
    {
        return (int) $value;
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault([
            'name' => 'No Category',
            'id' => null
        ]);
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

    // Add relationship to order details through product combinations
    public function orderDetails()
    {
        return $this->hasManyThrough(
            OrderDetail::class,
            ProductCombination::class,
            'product_id',
            'product_combination_id'
        );
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // Attributes
    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first()
            ?? $this->images()->first();
    }

    public function getImageUrlAttribute()
    {
        $image = $this->primary_image;
        return $image ? asset('storage/' . $image->image_path) : asset('images/no-image.png');
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

    public function getPriceAttribute()
    {
        if (!$this->has_variant) {
            return $this->base_price;
        }

        return $this->min_price;
    }
}