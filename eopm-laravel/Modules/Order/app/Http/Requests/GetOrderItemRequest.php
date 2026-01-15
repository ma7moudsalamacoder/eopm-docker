<?php

namespace Modules\Order\Http\Requests;

use Modules\Order\Rules\CanAccessToOrder;
use Illuminate\Foundation\Http\FormRequest;

class GetOrderItemRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->route('id')) {
            $this->merge([
                'id' => $this->route('id') ?? null
            ]);
        }
    }
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "id" => ["required","integer","exists:order_items,id", new CanAccessToOrder("OrderItem")]
        ];
    }

    public function messages()
    {
        return [
            "id.required" => "Order item id is required",
            "id.integer" => "Order item id must be integer",
            "id.exists" => "order item does not exist",
            "id.CanAccessToOrder" => "you can not access to this order item"
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
