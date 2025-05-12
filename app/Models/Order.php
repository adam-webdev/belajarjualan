<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'order_number',
        'subtotal',
        'shipping_cost',
        'discount_amount',
        'coupon_id',
        'coupon_code',
        'total',
        'status',
        'tracking_number',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function couponUsage()
    {
        return $this->hasOne(CouponUsage::class);
    }

    // Scopes
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Methods
    public function updateStatus($status, $tracking_number = null)
    {
        $this->status = $status;

        if ($tracking_number) {
            $this->tracking_number = $tracking_number;
        }

        $this->save();

        // Update payment status if needed
        if ($status === 'cancelled') {
            if ($this->payment) {
                $this->payment->update(['status' => 'failed']);
            }
        }

        return $this;
    }

    // Attributes
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getPaymentStatusAttribute()
    {
        if (!$this->payment) {
            return 'Unpaid';
        }

        $labels = [
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed',
        ];

        return $labels[$this->payment->status] ?? $this->payment->status;
    }

    public function getCanBeCancelledAttribute()
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    public function getIsCompletedAttribute()
    {
        return in_array($this->status, ['delivered']);
    }

    public function getIsCancelledAttribute()
    {
        return $this->status === 'cancelled';
    }
}