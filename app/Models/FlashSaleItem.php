<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'flash_sale_id',
        'product_combination_id',
        'stock_available',
        'stock_sold',
        'discount_price',
        'purchase_limit',
        'is_active',
    ];

    protected $casts = [
        'stock_available' => 'integer',
        'stock_sold' => 'integer',
        'discount_price' => 'decimal:2',
        'purchase_limit' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class);
    }

    public function productCombination()
    {
        return $this->belongsTo(ProductCombination::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_available', '>', 'stock_sold');
    }

    // Attributes
    public function getProductAttribute()
    {
        return $this->productCombination->product;
    }

    public function getRegularPriceAttribute()
    {
        return $this->productCombination->price;
    }

    public function getDiscountPercentAttribute()
    {
        $regularPrice = $this->regularPrice;

        if ($regularPrice == 0) {
            return 0;
        }

        return round(($regularPrice - $this->discount_price) / $regularPrice * 100);
    }

    public function getStockRemainingAttribute()
    {
        return max(0, $this->stock_available - $this->stock_sold);
    }

    public function getSoldPercentAttribute()
    {
        if ($this->stock_available == 0) {
            return 0;
        }

        return round(($this->stock_sold / $this->stock_available) * 100);
    }

    // Methods
    public function decreaseStock($quantity)
    {
        $this->stock_sold = min($this->stock_available, $this->stock_sold + $quantity);
        $this->save();

        return $this;
    }
}