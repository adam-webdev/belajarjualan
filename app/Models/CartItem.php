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
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
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

    // Attributes
    public function getProductAttribute()
    {
        return $this->productCombination->product;
    }

    public function getPriceAttribute()
    {
        return $this->productCombination->price;
    }

    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
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