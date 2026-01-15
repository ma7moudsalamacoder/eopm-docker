<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Transformers\UserResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {   
        return [
            "order_id" => $this->id,
            "user" => $this->user,
            "status" => $this->status,
            "grand_total" => $this->grandTotal."$",
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            "items" => $this->items,
            "payments" => $this->payments ?? []
        ];
    }
}
