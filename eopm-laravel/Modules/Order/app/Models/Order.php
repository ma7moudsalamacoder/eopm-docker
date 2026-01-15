<?php

namespace Modules\Order\Models;

use App\Models\User;
use Modules\Order\Models\OrderItem;
// use Modules\Order\Database\Factories\OrderFactory;
use Modules\Payment\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Modules\System\Traits\FormatAuditDate;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use FormatAuditDate;
    protected $table = "orders";

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['user_id', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'order_id');
    }


    public function calculateGrandTotal(): float
    {
        $items = $this->relationLoaded('items') ? $this->items : $this->items()->get();
        $total = $items->sum(function (OrderItem $item) {
            return $item->calculateTotalPrice();
        });
        return round((float) $total, 2);
    }

    public function getGrandTotalAttribute(): float
    {
        return $this->calculateGrandTotal();
    }

}
