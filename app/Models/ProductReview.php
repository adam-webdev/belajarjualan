<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',
        'comment',
        'images',
        'is_verified',
    ];

    protected $casts = [
        'rating' => 'integer',
        'images' => 'array',
        'is_verified' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeRated($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    // Methods
    public function verify()
    {
        $this->is_verified = true;
        $this->save();

        return $this;
    }

    // Attributes
    public function getRatingStarsAttribute()
    {
        $stars = '';

        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '★';
            } else {
                $stars .= '☆';
            }
        }

        return $stars;
    }
}