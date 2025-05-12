<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_combination_id',
        'quantity',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
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

    public function getProductNameAttribute()
    {
        $product = $this->product;
        $combination = $this->productCombination;

        if ($product->has_variant) {
            return $product->name . ' - ' . $combination->optionsText;
        }

        return $product->name;
    }

    // Methods
    public static function createFromCartItem($cartItem, $orderId)
    {
        return self::create([
            'order_id' => $orderId,
            'product_combination_id' => $cartItem->product_combination_id,
            'quantity' => $cartItem->quantity,
            'price' => $cartItem->price,
            'subtotal' => $cartItem->subtotal,
        ]);
    }
}