<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method',
        'amount',
        'discount_amount',
        'coupon_id',
        'coupon_code',
        'status',
        'payment_proof',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    // Scopes
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Methods
    public function markAsPaid()
    {
        $this->status = 'paid';
        $this->paid_at = now();
        $this->save();

        // Update order status
        $this->order->updateStatus('processing');

        return $this;
    }

    public function markAsFailed()
    {
        $this->status = 'failed';
        $this->save();

        return $this;
    }

    public function refund()
    {
        $this->status = 'refunded';
        $this->save();

        // Update order status
        $this->order->updateStatus('cancelled');

        return $this;
    }
}