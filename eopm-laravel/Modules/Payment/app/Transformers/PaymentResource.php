<?php

namespace Modules\Payment\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "payment_id"=> $this->id,
            "order_id" => $this->order_id,
            "payer_name" => $this->payer_name,
            "amount" => $this->amount,
            "method" => $this->method,
            "transaction_id" => $this->payload['transaction_id'] ?? "N/A",
            "card" => $this->payload['card'] ?? 'N/A',
            "status" => $this->status,
            "created_at" => $this->created_at,
            "updated_at"=> $this->updated_at
        ];
    }
}
