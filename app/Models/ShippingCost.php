<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_method_id',
        'province',
        'city',
        'cost',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
    ];

    // Relationships
    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }
}