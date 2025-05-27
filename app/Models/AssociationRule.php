<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssociationRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'antecedent_product_ids',
        'antecedent_names',
        'consequent_product_ids',
        'consequent_names',
        'support',
        'confidence',
        'lift',
    ];

    protected $casts = [
        'antecedent_product_ids' => 'array',
        'antecedent_names' => 'array',
        'consequent_product_ids' => 'array',
        'consequent_names' => 'array',
    ];

    // Opsional: Relasi ke Product jika Anda ingin langsung memuat produk terkait
    // Ini akan rumit jika antecedent/consequent punya lebih dari 1 item.
    // Query di controller (seperti yang akan dijelaskan) lebih fleksibel.
}