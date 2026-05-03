<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'category',
        'buy_price',
        'sell_price',
        'current_stock',
        'min_stock_alert',
        'image',
        'is_available_online',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function posOrderItems(): HasMany
    {
        return $this->hasMany(PosOrderItem::class);
    }

    protected static function booted()
    {
        static::created(function ($product) {
            if ($product->current_stock > 0) {
                $product->inventoryLogs()->create([
                    'type' => 'IN',
                    'quantity' => $product->current_stock,
                    'reason' => 'Stok awal produk baru',
                ]);

                $totalCost = $product->current_stock * $product->buy_price;
                if ($totalCost > 0) {
                    \App\Models\Transaction::create([
                        'user_id' => $product->user_id,
                        'type' => 'EXPENSE',
                        'amount' => $totalCost,
                        'category' => 'Modal Stok Awal',
                        'notes' => 'Stok awal produk: ' . $product->name . ' (' . $product->current_stock . ' unit)',
                    ]);
                }
            }
        });

        static::updated(function ($product) {
            if ($product->isDirty('current_stock')) {
                $oldStock = $product->getOriginal('current_stock');
                $newStock = $product->current_stock;
                $diff = $newStock - $oldStock;

                if ($diff != 0) {
                    $product->inventoryLogs()->create([
                        'type' => $diff > 0 ? 'IN' : 'OUT',
                        'quantity' => abs($diff),
                        'reason' => 'Penyesuaian stok (Manual)',
                    ]);

                    if ($diff > 0) {
                        $totalCost = $diff * $product->buy_price;
                        if ($totalCost > 0) {
                            \App\Models\Transaction::create([
                                'user_id' => $product->user_id,
                                'type' => 'EXPENSE',
                                'amount' => $totalCost,
                                'category' => 'Belanja Stok',
                                'notes' => 'Penambahan stok manual: ' . $product->name . ' (' . $diff . ' unit)',
                            ]);
                        }
                    }
                }
            }
        });
    }
}
