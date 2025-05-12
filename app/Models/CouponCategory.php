<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id',
        'category_id',
    ];

    // Relationships
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Methods
    public static function syncCategories($couponId, $categoryIds)
    {
        // Delete existing records
        self::where('coupon_id', $couponId)->delete();

        // Create new records
        $data = collect($categoryIds)->map(function ($categoryId) use ($couponId) {
            return [
                'coupon_id' => $couponId,
                'category_id' => $categoryId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        return self::insert($data);
    }
}