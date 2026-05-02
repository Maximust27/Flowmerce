<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PosOrder extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total',
        'payment_method',
        'status',
        'guest_order_id',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:4',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PosOrderItem::class);
    }

    public function guestOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(GuestOrder::class);
    }

    /**
     * Generate unique order number: FLW-YYYYMMDD-XXXXX
     * Uses timestamp + random suffix to avoid race conditions
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'FLW-' . now()->format('Ymd') . '-';
        $random = strtoupper(substr(uniqid(), -5));
        $orderNumber = $prefix . $random;

        // Ensure uniqueness in the rare case of collision
        while (static::where('order_number', $orderNumber)->exists()) {
            $random = strtoupper(substr(uniqid(), -5));
            $orderNumber = $prefix . $random;
        }

        return $orderNumber;
    }
}
