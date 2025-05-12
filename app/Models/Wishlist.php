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

    // Attributes
    public function getProductAttribute()
    {
        return $this->productCombination->product;
    }

    // Methods
    public static function addItem($userId, $productCombinationId)
    {
        return self::firstOrCreate([
            'user_id' => $userId,
            'product_combination_id' => $productCombinationId,
        ]);
    }

    public static function removeItem($userId, $productCombinationId)
    {
        return self::where('user_id', $userId)
            ->where('product_combination_id', $productCombinationId)
            ->delete();
    }

    public static function isInWishlist($userId, $productCombinationId)
    {
        return self::where('user_id', $userId)
            ->where('product_combination_id', $productCombinationId)
            ->exists();
    }
}