<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCombinationValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_combination_id',
        'product_option_value_id',
    ];

    // Relationships
    public function combination()
    {
        return $this->belongsTo(ProductCombination::class, 'product_combination_id');
    }

    public function optionValue()
    {
        return $this->belongsTo(ProductOptionValue::class, 'product_option_value_id');
    }
}