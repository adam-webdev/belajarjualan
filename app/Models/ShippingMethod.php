<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function shippingCosts()
    {
        return $this->hasMany(ShippingCost::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Methods
    public function getCostForLocation($province, $city)
    {
        return $this->shippingCosts()
            ->where('province', $province)
            ->where('city', $city)
            ->first()
            ->cost ?? null;
    }
}