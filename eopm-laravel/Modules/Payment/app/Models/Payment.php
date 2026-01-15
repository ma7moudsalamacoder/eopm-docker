<?php

namespace Modules\Payment\Models;

use Modules\Order\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\System\Traits\FormatAuditDate;

// use Modules\Payment\Database\Factories\PaymentFactory;

class Payment extends Model
{
    use FormatAuditDate;
    protected $table = 'payments';

    protected $fillable = [
        'payer_name',
        'amount',
        'method',
        'payload',
        'status',
        'order_id',
    ];


    protected $casts = [
        'amount' => 'decimal:2',
        'payload' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

}
