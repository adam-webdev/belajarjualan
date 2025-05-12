<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id',
        'product_id',
    ];

    // Relationships
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Methods
    public static function syncProducts($couponId, $productIds)
    {
        // Delete existing records
        self::where('coupon_id', $couponId)->delete();

        // Create new records
        $data = collect($productIds)->map(function ($productId) use ($couponId) {
            return [
                'coupon_id' => $couponId,
                'product_id' => $productId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        return self::insert($data);
    }
}