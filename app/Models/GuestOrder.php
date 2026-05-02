<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GuestOrder extends Model
{
    protected $fillable = [
        'user_id',
        'table_id',
        'order_code',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total',
        'payment_method',
        'payment_status',
        'order_status',
        'notes',
        'expires_at',
    ];

    protected $casts = [
        'subtotal'   => 'decimal:2',
        'tax_rate'   => 'decimal:4',
        'tax_amount' => 'decimal:2',
        'total'      => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(GuestOrderItem::class);
    }

    public function posOrder(): HasOne
    {
        return $this->hasOne(PosOrder::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Generate unique order code: KSR-XXXXX
     */
    public static function generateOrderCode(): string
    {
        $code = 'KSR-' . strtoupper(substr(uniqid(), -5));
        while (static::where('order_code', $code)->exists()) {
            $code = 'KSR-' . strtoupper(substr(uniqid(), -5));
        }
        return $code;
    }
}
