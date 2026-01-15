<?php

namespace Modules\Order\Http\Requests;

use Modules\Order\Rules\CanAccessToOrder;
use Illuminate\Foundation\Http\FormRequest;

class GetOrderRequest extends FormRequest
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
            "id" => ["required", "integer", "exists:orders,id", new CanAccessToOrder("Order")]
        ];
    }

    public function messages()
    {
        return [
            "id.required" => "Order id is required",
            "id.integer" => "order id must be integer",
            "id.exists" => "order does not exist",
            "id.CanAccessToOrder" => "you can not access to this order"

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
