<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subtotal',
        'discount_amount',
        'shipping_cost',
        'total'
    ];

    protected $attributes = [
        'shipping_cost' => 0,
        'discount_amount' => 0,
        'subtotal' => 0,
        'total' => 0
    ];

    protected $casts = [
        'shipping_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Attributes
    public function getSubtotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->productCombination->price;
        });
    }

    public function getTotalAttribute()
    {
        return $this->subtotal - $this->discount_amount + $this->shipping_cost;
    }

    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

    // Methods
    public function addItem($productCombinationId, $quantity = 1)
    {
        $item = $this->items()->where('product_combination_id', $productCombinationId)->first();

        if ($item) {
            $item->quantity += $quantity;
            $item->save();
            return $item;
        }

        return $this->items()->create([
            'product_combination_id' => $productCombinationId,
            'quantity' => $quantity,
        ]);
    }

    public function updateItem($cartItemId, $quantity)
    {
        $item = $this->items()->find($cartItemId);

        if (!$item) {
            return false;
        }

        if ($quantity <= 0) {
            $item->delete();
            return true;
        }

        $item->quantity = $quantity;
        $item->save();

        return $item;
    }

    public function removeItem($cartItemId)
    {
        return $this->items()->where('id', $cartItemId)->delete();
    }

    public function clear()
    {
        return $this->items()->delete();
    }
}