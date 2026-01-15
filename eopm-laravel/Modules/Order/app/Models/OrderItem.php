<?php

namespace Modules\Order\Models;

use Modules\Order\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Modules\System\Traits\FormatAuditDate;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use FormatAuditDate;
    protected $table="order_items";

    protected $fillable = ['order_id', 'product', 'qty', 'price'];

    protected $casts = [
        'qty' => 'integer',
        'price' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function calculateTotalPrice(): float
    {
        $qty = (int) $this->qty;
        $price = (float) $this->price;
        return round($qty * $price, 2);
    }

}