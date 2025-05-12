<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone',
        'province',
        'city',
        'district',
        'postal_code',
        'address_detail',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function setAsDefault()
    {
        // Reset all defaults for this user
        Address::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        $this->is_default = true;
        $this->save();

        return $this;
    }

    public function getFullAddressAttribute()
    {
        return "{$this->address_detail}, {$this->district}, {$this->city}, {$this->province} {$this->postal_code}";
    }
}