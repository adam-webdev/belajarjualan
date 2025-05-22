<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_combination_id',
        'quantity'
    ];

    protected $casts = [
        'quantity' => 'integer'
    ];

    // Relationships
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function productCombination()
    {
        return $this->belongsTo(ProductCombination::class);
    }

    public function product()
    {
        return $this->hasOneThrough(
            Product::class,
            ProductCombination::class,
            'id', // Foreign key on product_combinations table
            'id', // Foreign key on products table
            'product_combination_id', // Local key on cart_items table
            'product_id' // Local key on product_combinations table
        );
    }

    // Attributes
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    public function getNameAttribute()
    {
        $product = $this->product;
        $combination = $this->productCombination;

        if ($product->has_variant) {
            return $product->name . ' - ' . $combination->optionsText;
        }

        return $product->name;
    }
}