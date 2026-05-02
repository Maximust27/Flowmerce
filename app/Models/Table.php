<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    protected $fillable = [
        'user_id',
        'table_number',
        'table_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guestOrders(): HasMany
    {
        return $this->hasMany(GuestOrder::class);
    }

    /**
     * Generate unique table code: TBL-XXXXX
     */
    public static function generateTableCode(): string
    {
        $code = 'TBL-' . strtoupper(substr(uniqid(), -5));
        while (static::where('table_code', $code)->exists()) {
            $code = 'TBL-' . strtoupper(substr(uniqid(), -5));
        }
        return $code;
    }
}
