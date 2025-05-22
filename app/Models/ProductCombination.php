<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCombination extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'stock',
        'weight',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function combinationValues()
    {
        return $this->hasMany(ProductCombinationValue::class);
    }

    // Alias for combinationValues - to fix compatibility with existing code
    public function values()
    {
        return $this->hasMany(ProductCombinationValue::class);
    }

    public function optionValues()
    {
        return $this->belongsToMany(
            ProductOptionValue::class,
            'product_combination_values',
            'product_combination_id',
            'product_option_value_id'
        );
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function flashSaleItems()
    {
        return $this->hasMany(FlashSaleItem::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // Scopes
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Methods
    public function decreaseStock($quantity)
    {
        $this->stock = max(0, $this->stock - $quantity);
        $this->save();

        return $this;
    }

    public function increaseStock($quantity)
    {
        $this->stock += $quantity;
        $this->save();

        return $this;
    }

    // Attributes
    public function getOptionsTextAttribute()
    {
        return $this->optionValues()
            ->with('option')
            ->get()
            ->map(function($value) {
                return $value->option->name . ': ' . $value->value;
            })
            ->implode(', ');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($productCombination) {
            // Delete related wishlist items
            Wishlist::where('product_combination_id', $productCombination->id)->delete();
        });
    }
}