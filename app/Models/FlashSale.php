<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(FlashSaleItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        $now = now();
        return $query->where('is_active', true)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now);
    }

    public function scopeUpcoming($query)
    {
        $now = now();
        return $query->where('is_active', true)
            ->where('start_time', '>', $now);
    }

    public function scopePast($query)
    {
        $now = now();
        return $query->where('end_time', '<', $now);
    }

    // Attributes
    public function getIsActiveNowAttribute()
    {
        $now = now();
        return $this->is_active &&
            $now->greaterThanOrEqualTo($this->start_time) &&
            $now->lessThanOrEqualTo($this->end_time);
    }

    public function getIsUpcomingAttribute()
    {
        return $this->is_active && now()->lessThan($this->start_time);
    }

    public function getIsExpiredAttribute()
    {
        return now()->greaterThan($this->end_time);
    }

    public function getProgressPercentAttribute()
    {
        if ($this->isExpired) {
            return 100;
        }

        if ($this->isUpcoming) {
            return 0;
        }

        $total = $this->end_time->diffInSeconds($this->start_time);
        $elapsed = now()->diffInSeconds($this->start_time);

        return min(100, max(0, ($elapsed / $total) * 100));
    }

    public function getTimeRemainingAttribute()
    {
        if ($this->isExpired) {
            return 'Expired';
        }

        if ($this->isUpcoming) {
            return 'Starts in ' . now()->diffForHumans($this->start_time);
        }

        return 'Ends in ' . now()->diffForHumans($this->end_time);
    }
}