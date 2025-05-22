<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_combination_id',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productCombination()
    {
        return $this->belongsTo(ProductCombination::class);
    }

    // Get product through productCombination
    public function getProduct()
    {
        return $this->productCombination ? $this->productCombination->product : null;
    }

    // Methods
    public static function addItem($userId, $productCombinationId)
    {
        try {
            // Verify product combination exists
            $productCombination = ProductCombination::find($productCombinationId);
            if (!$productCombination) {
                return null;
            }

            return self::firstOrCreate([
                'user_id' => $userId,
                'product_combination_id' => $productCombinationId,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error adding item to wishlist: ' . $e->getMessage());
            return null;
        }
    }

    public static function removeItem($userId, $productCombinationId)
    {
        try {
            return self::where('user_id', $userId)
                ->where('product_combination_id', $productCombinationId)
                ->delete();
        } catch (\Exception $e) {
            \Log::error('Error removing item from wishlist: ' . $e->getMessage());
            return false;
        }
    }

    public static function isInWishlist($userId, $productCombinationId)
    {
        try {
            return self::where('user_id', $userId)
                ->where('product_combination_id', $productCombinationId)
                ->exists();
        } catch (\Exception $e) {
            \Log::error('Error checking wishlist item: ' . $e->getMessage());
            return false;
        }
    }

    public static function addAllToCart($userId)
    {
        try {
            $wishlistItems = self::where('user_id', $userId)
                ->with(['productCombination.product'])
                ->get();

            if ($wishlistItems->isEmpty()) {
                return collect();
            }

            $cart = Cart::firstOrCreate(['user_id' => $userId]);

            foreach ($wishlistItems as $item) {
                if ($item->productCombination && $item->productCombination->product) {
                    $cart->addItem($item->product_combination_id, 1);
                }
            }

            return $wishlistItems;
        } catch (\Exception $e) {
            \Log::error('Error adding wishlist items to cart: ' . $e->getMessage());
            return collect();
        }
    }

    public static function moveToCart($userId, $wishlistId)
    {
        try {
            $wishlistItem = self::where('user_id', $userId)
                ->where('id', $wishlistId)
                ->with('productCombination.product')
                ->first();

            if (!$wishlistItem || !$wishlistItem->productCombination || !$wishlistItem->productCombination->product) {
                return false;
            }

            $cart = Cart::firstOrCreate(['user_id' => $userId]);
            $cart->addItem($wishlistItem->product_combination_id, 1);
            $wishlistItem->delete();
            return true;
        } catch (\Exception $e) {
            \Log::error('Error moving wishlist item to cart: ' . $e->getMessage());
            return false;
        }
    }
}